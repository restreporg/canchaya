import 'package:dio/dio.dart';
import '../models/user.dart';
import 'api_client.dart';

class AuthService {
  final _client = ApiClient();

  Future<AppUser> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String? phone,
  }) async {
    try {
      final res = await _client.dio.post(
        '/register',
        data: {
          'name': name,
          'email': email,
          'phone': phone,
          'password': password,
          'password_confirmation': passwordConfirmation,
        },
      );

      final token = res.data['token'] as String;
      await _client.saveToken(token);
      return AppUser.fromJson(res.data['user']);
    } on DioException catch (e) {
      throw _client.handleError(e);
    }
  }

  Future<AppUser> login({
    required String email,
    required String password,
  }) async {
    try {
      final res = await _client.dio.post(
        '/login',
        data: {'email': email, 'password': password},
      );

      final token = res.data['token'] as String;
      await _client.saveToken(token);
      return AppUser.fromJson(res.data['user']);
    } on DioException catch (e) {
      throw _client.handleError(e);
    }
  }

  Future<AppUser?> getCurrentUser() async {
    final token = await _client.getToken();
    if (token == null) return null;

    try {
      final res = await _client.dio.get('/me');
      return AppUser.fromJson(res.data['user']);
    } on DioException {
      // Token inválido o expirado: lo limpiamos.
      await _client.clearToken();
      return null;
    }
  }

  Future<void> logout() async {
    try {
      await _client.dio.post('/logout');
    } on DioException {
      // Aunque falle en el servidor, igual limpiamos el token localmente.
    }
    await _client.clearToken();
  }
}
