/// Excepción unificada para errores que vienen del backend Laravel,
/// para poder mostrar mensajes claros en la UI.
class ApiException implements Exception {
  final String message;
  final Map<String, dynamic>? errors;
  final int? statusCode;

  ApiException(this.message, {this.errors, this.statusCode});

  /// Primer mensaje de error de validación (si lo hay), útil para
  /// mostrar en un SnackBar sin tener que iterar el mapa.
  String get firstFieldError {
    if (errors != null && errors!.isNotEmpty) {
      final firstKey = errors!.keys.first;
      final firstValue = errors![firstKey];
      if (firstValue is List && firstValue.isNotEmpty) {
        return firstValue.first.toString();
      }
    }
    return message;
  }

  @override
  String toString() => message;
}
