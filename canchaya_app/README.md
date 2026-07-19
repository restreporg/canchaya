# CanchaYa App Móvil

Aplicación móvil (Flutter) de **CanchaYa**, plataforma de reservas de canchas deportivas. Permite a los clientes explorar canchas, reservar horarios, pagar y dejar reseñas desde su celular, consumiendo la API REST del backend en Laravel.

> 📌 Este repositorio corresponde únicamente a la app móvil. Para la documentación del backend (base de datos, relaciones, lógica de negocio completa y pruebas de la API con Insomnia), consulta el README del proyecto backend: `[link al repo/README del backend]`.

---

# Estudiantes
- Santiago Restrepo Santamaria.
- Juan Diego Perez.
- David Esnaider Quiñonez.

---

# Descripción

La app permite a los clientes:
- Registrarse e iniciar sesión.
- Explorar canchas disponibles filtrando por deporte y ubicación.
- Ver horarios disponibles en tiempo real.
- Realizar reservas y pagos.
- Consultar y cancelar sus propias reservas.
- Dejar reseñas de reservas completadas.

La app se conecta al backend de Laravel mediante su API REST para toda la lógica de negocio, autenticación y persistencia de datos.

---

# Tecnologías principales

| Tecnología | Uso |
|------------|-----|
| Flutter (SDK ^3.9.0) | Framework de UI multiplataforma |
| Dart | Lenguaje del proyecto |
| `provider` ^6.1.2 | Manejo de estado |
| `dio` ^5.7.0 | Cliente HTTP para consumir la API |
| `flutter_secure_storage` ^9.2.2 | Almacenamiento local seguro del token de sesión |
| `intl` ^0.19.0 | Formateo de fechas, horas y números |
| `cupertino_icons` ^1.0.8 | Íconos estilo iOS |
| `flutter_lints` ^5.0.0 | Reglas de linting (dev) |

---

# Requisitos

- [Flutter SDK](https://docs.flutter.dev/get-started/install) (versión estable más reciente)
- Android Studio (SDK de Android / emulador) y/o Xcode (solo en macOS, para iOS)
- Editor recomendado: VS Code con extensiones de Flutter y Dart
- Backend de CanchaYa corriendo localmente (ver README del backend)

Verifica tu entorno con:
```
flutter doctor
```

---

# Instalación y ejecución local

(BASH)

1. Clonar el repositorio
```
git clone 
cd canchaya_app
```

2. Instalar las dependencias del proyecto
```
flutter pub get
```

3. Configurar la URL de la API

   La app usa `Dio` para consumir la API. Abre `lib/config/api_config.dart` y actualiza la URL base según tu entorno:

   - Servidor local con `php artisan serve`:
     ```
     http://127.0.0.1:8000/api
     ```
   - Laravel Herd:
     ```
     https://canchaya.test/api
     ```
   - Emulador Android (recuerda que `localhost` del computador equivale a `10.0.2.2` desde el emulador):
     ```
     http://10.0.2.2:8000/api
     ```

4. Verificar dispositivos disponibles (emulador o dispositivo físico conectado)
```
flutter devices
```

5. Ejecutar la aplicación
```
flutter run
```

# Credenciales de prueba
| Rol | Email | Contraseña |
|-----|-------|------------|
| Admin | admin@canchaya.com | password |
| Cliente | carlos@gmail.com | password |

---

# Generar build de release

**Android (APK):**
```
flutter build apk --release
```
El archivo se genera en `build/app/outputs/flutter-apk/app-release.apk`.

**Android (AAB, para Google Play):**
```
flutter build appbundle --release
```

**iOS** *(requiere macOS y Xcode):*
```
flutter build ios --release
```

---

# Estructura del proyecto

```
canchaya_app/
├── android/          # Configuración nativa Android
├── ios/              # Configuración nativa iOS
├── lib/              # Código fuente de la app (Dart)
│   ├── config/
│   │   └── api_config.dart        # URL base de la API y configuración de conexión
│   ├── models/
│   │   ├── court.dart              # Modelo de cancha
│   │   ├── reservation.dart        # Modelo de reserva
│   │   ├── schedule.dart           # Modelo de horario
│   │   └── user.dart               # Modelo de usuario
│   ├── providers/
│   │   └── auth_provider.dart      # Estado de autenticación (Provider)
│   ├── screens/
│   │   ├── court_detail_screen.dart
│   │   ├── courts_screen.dart
│   │   ├── home_screen.dart
│   │   ├── login_screen.dart
│   │   ├── register_screen.dart
│   │   └── reservations_screen.dart
│   ├── services/
│   │   ├── api_client.dart         # Instancia y configuración de Dio
│   │   ├── api_exception.dart      # Manejo de errores de la API
│   │   ├── auth_service.dart       # Login, registro, logout
│   │   ├── court_service.dart      # Consumo de endpoints de canchas
│   │   └── reservation_service.dart # Consumo de endpoints de reservas
│   └── main.dart                   # Punto de entrada de la app
├── test/             # Pruebas unitarias/widget
├── web/              # Soporte para versión web (si aplica)
├── pubspec.yaml      # Dependencias del proyecto
└── README.md
```

> Ajusta este árbol según la organización real de `lib/` en el proyecto.

---

# Pantallas principales

| Pantalla | Descripción |
|----------|-------------|
| `login_screen.dart` | Inicio de sesión |
| `register_screen.dart` | Registro de nuevos usuarios |
| `home_screen.dart` | Pantalla principal / punto de entrada tras autenticarse |
| `courts_screen.dart` | Listado de canchas disponibles |
| `court_detail_screen.dart` | Detalle de una cancha (horarios, información, reserva) |
| `reservations_screen.dart` | Listado de reservas del usuario ("Mis Reservas") |

---

# Flujo de uso (resumen)

1. El usuario se registra o inicia sesión.
2. Explora canchas disponibles filtrando por deporte y/o ubicación.
3. Selecciona una cancha, fecha y horario disponible.
4. Confirma la reserva y realiza el pago.
5. Puede consultar el estado de sus reservas (`pendiente`, `confirmada`, `completada`, `cancelada`) desde la sección "Mis Reservas".
6. Una vez la reserva está en estado `completada`, puede dejar una reseña con calificación de 1 a 5 estrellas.

> El detalle completo de la lógica de negocio (estados, reglas, protección de rutas) está documentado en el README del backend.

---

# Capturas de pantalla

*(Agregar capturas de las pantallas principales: login/registro, listado de canchas, detalle de cancha, reserva, pago, mis reservas, reseñas)*

---

# Autenticación

La app utiliza autenticación por token contra la API del backend (Sanctum/Passport):

1. Al iniciar sesión, la API retorna un `token`.
2. El token se guarda localmente de forma segura con `flutter_secure_storage`.
3. Cada petición a rutas protegidas envía el token como `Bearer Token` en el header `Authorization`, configurado mediante un interceptor de `Dio`.
4. El estado de sesión del usuario (autenticado, datos del usuario, rol) se gestiona globalmente con `Provider`.
5. Al cerrar sesión, el token se invalida en el backend y se elimina del almacenamiento seguro local.

---

# Problemas comunes

| Problema | Posible solución |
|----------|-------------------|
| La app no conecta con la API | Verifica que el backend esté corriendo y que la URL configurada sea la correcta según tu entorno (ver sección de instalación). |
| `401 Unauthorized` | El token expiró o la sesión se cerró. Vuelve a iniciar sesión. |
| Error al compilar en Android | Ejecuta `flutter clean` y luego `flutter pub get`. |
| Emulador no encuentra el backend | Recuerda usar `10.0.2.2` en vez de `localhost` en el emulador Android. |