/// Cambia esta URL según dónde estés corriendo la app:
///
/// - Emulador Android            -> http://10.0.2.2:8000/api
/// - Emulador iOS / Chrome web   -> http://127.0.0.1:8000/api
/// - Celular físico (misma WiFi) -> http://TU_IP_LOCAL:8000/api
///                                  (mira tu IP con "ipconfig" en PowerShell,
///                                   busca "Dirección IPv4", ej. 192.168.1.15)
///
/// Recuerda que tu backend debe estar corriendo con:
///   php artisan serve --host=0.0.0.0
/// para que otros dispositivos en la red puedan alcanzarlo.
class ApiConfig {
  static const String baseUrl = 'http://127.0.0.1:8000/api';

  /// Locale usado para formatear fechas en la UI (ej. "lunes 15 jul").
  static const String locale = 'es_CO';

  /// Nota: el backend Laravel ya está configurado con
  /// 'timezone' => 'America/Bogota' en config/app.php, así que las
  /// fechas que llegan del API ya vienen en hora local de Colombia.
  /// No se necesita conversión de zona horaria en el frontend.
}
