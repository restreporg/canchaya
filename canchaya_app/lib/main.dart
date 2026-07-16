import 'package:flutter/material.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'package:provider/provider.dart';
import 'config/api_config.dart';
import 'providers/auth_provider.dart';
import 'screens/home_screen.dart';
import 'screens/login_screen.dart';

/// Observador global de rutas, usado por pantallas (como ReservationsScreen)
/// que necesitan saber cuándo el usuario vuelve a verlas tras navegar a
/// otra ruta encima (ej. crear una reserva y volver a "Mis reservas").
final routeObserver = RouteObserver<ModalRoute<void>>();

/// Paleta de marca de Canchaya, tomada del sitio web (canchas.xyz).
class AppColors {
  static const verde = Color(0xFF1DB954);
  static const verdeDark = Color(0xFF17A045);
  static const verdeOscuro = Color(0xFF0D5C2E);
}

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await initializeDateFormatting(ApiConfig.locale);
  runApp(const CanchaYaApp());
}

class CanchaYaApp extends StatelessWidget {
  const CanchaYaApp({super.key});

  @override
  Widget build(BuildContext context) {
    final colorScheme = ColorScheme.fromSeed(
      seedColor: AppColors.verde,
      primary: AppColors.verde,
      brightness: Brightness.light,
    );

    return ChangeNotifierProvider(
      create: (_) => AuthProvider()..tryAutoLogin(),
      child: MaterialApp(
        title: 'CanchaYa',
        debugShowCheckedModeBanner: false,
        navigatorObservers: [routeObserver],
        theme: ThemeData(
          useMaterial3: true,
          colorScheme: colorScheme,
          scaffoldBackgroundColor: const Color(
            0xFFF8F9FA,
          ), // similar a bg-light de Bootstrap

          appBarTheme: AppBarTheme(
            backgroundColor: colorScheme.surface,
            foregroundColor: Colors.black87,
            elevation: 0,
            centerTitle: false,
            titleTextStyle: const TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.bold,
              color: Colors.black87,
            ),
          ),

          // Botón principal: igual al .btn-verde del sitio.
          filledButtonTheme: FilledButtonThemeData(
            style:
                FilledButton.styleFrom(
                  backgroundColor: AppColors.verde,
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(
                    vertical: 14,
                    horizontal: 24,
                  ),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(10),
                  ),
                  textStyle: const TextStyle(
                    fontWeight: FontWeight.w600,
                    fontSize: 16,
                  ),
                ).copyWith(
                  // Efecto hover -> verde-dark, replicando .btn-verde:hover
                  overlayColor: WidgetStateProperty.all(
                    AppColors.verdeDark.withValues(alpha: 0.15),
                  ),
                ),
          ),

          outlinedButtonTheme: OutlinedButtonThemeData(
            style: OutlinedButton.styleFrom(
              foregroundColor: AppColors.verde,
              side: const BorderSide(color: AppColors.verde),
              padding: const EdgeInsets.symmetric(vertical: 14, horizontal: 24),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(10),
              ),
              textStyle: const TextStyle(
                fontWeight: FontWeight.w600,
                fontSize: 16,
              ),
            ),
          ),

          textButtonTheme: TextButtonThemeData(
            style: TextButton.styleFrom(foregroundColor: AppColors.verde),
          ),

          // Tarjetas: bordes redondeados 16px + sombra suave, igual a .card-feature
          cardTheme: CardThemeData(
            elevation: 2,
            shadowColor: Colors.black12,
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
            ),
            margin: EdgeInsets.zero,
          ),

          inputDecorationTheme: InputDecorationTheme(
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(10),
              borderSide: const BorderSide(color: AppColors.verde, width: 2),
            ),
          ),

          chipTheme: ChipThemeData(
            labelStyle: const TextStyle(color: Colors.white, fontSize: 12),
            padding: EdgeInsets.zero,
          ),

          navigationBarTheme: NavigationBarThemeData(
            indicatorColor: AppColors.verde.withValues(alpha: 0.15),
            labelTextStyle: WidgetStateProperty.resolveWith((states) {
              final selected = states.contains(WidgetState.selected);
              return TextStyle(
                fontSize: 12,
                fontWeight: selected ? FontWeight.bold : FontWeight.normal,
                color: selected ? AppColors.verdeOscuro : Colors.grey,
              );
            }),
            iconTheme: WidgetStateProperty.resolveWith((states) {
              final selected = states.contains(WidgetState.selected);
              return IconThemeData(
                color: selected ? AppColors.verde : Colors.grey,
              );
            }),
          ),

          textTheme: const TextTheme(
            headlineMedium: TextStyle(fontWeight: FontWeight.w800),
            headlineSmall: TextStyle(fontWeight: FontWeight.bold),
            titleMedium: TextStyle(fontWeight: FontWeight.bold),
          ),
        ),
        home: const _AuthGate(),
      ),
    );
  }
}

/// Muestra un loader mientras se verifica si hay una sesión guardada,
/// y luego decide entre LoginScreen o HomeScreen automáticamente.
class _AuthGate extends StatelessWidget {
  const _AuthGate();

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();

    switch (auth.status) {
      case AuthStatus.unknown:
        return const Scaffold(body: Center(child: CircularProgressIndicator()));
      case AuthStatus.authenticated:
        return const HomeScreen();
      case AuthStatus.unauthenticated:
        return const LoginScreen();
    }
  }
}
