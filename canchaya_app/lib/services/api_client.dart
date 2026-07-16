import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../config/api_config.dart';
import 'api_exception.dart';

/// Envuelve un Dio configurado para hablar con la API de Laravel:
/// - agrega el token guardado en cada request (si existe)
/// - siempre manda Accept: application/json (clave para que Laravel
///   devuelva JSON en vez de HTML en los errores)
/// - traduce los errores de Dio a ApiException con mensajes legibles
class ApiClient {
  static final ApiClient _instance = ApiClient._internal();
  factory ApiClient() => _instance;

  late final Dio dio;
  final _storage = const FlutterSecureStorage();
  static const _tokenKey = 'auth_token';

  ApiClient._internal() {
    dio = Dio(BaseOptions(
      baseUrl: ApiConfig.baseUrl,
      connectTimeout: const Duration(seconds: 15),
      receiveTimeout: const Duration(seconds: 15),
      headers: {'Accept': 'application/json'},
    ));

    dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        final token = await _storage.read(key: _tokenKey);
        if (token != null) {
          options.headers['Authorization'] = 'Bearer $token';
        }
        handler.next(options);
      },
    ));
  }

  Future<void> saveToken(String token) async {
    await _storage.write(key: _tokenKey, value: token);
  }

  Future<String?> getToken() async {
    return _storage.read(key: _tokenKey);
  }

  Future<void> clearToken() async {
    await _storage.delete(key: _tokenKey);
  }

  /// Convierte cualquier DioException en una ApiException con mensaje claro.
  ApiException handleError(DioException e) {
    final response = e.response;

    if (response == null) {
      return ApiException(
        'No se pudo conectar con el servidor. Revisa tu conexión o la URL en ApiConfig.',
      );
    }

    final data = response.data;
    String message = 'Ocurrió un error inesperado.';
    Map<String, dynamic>? errors;

    if (data is Map<String, dynamic>) {
      message = data['message']?.toString() ?? message;
      if (data['errors'] is Map) {
        errors = Map<String, dynamic>.from(data['errors']);
      }
    }

    if (response.statusCode == 401) {
      message = 'Tu sesión expiró. Inicia sesión de nuevo.';
    }

    return ApiException(message, errors: errors, statusCode: response.statusCode);
  }
}
