import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:provider/provider.dart';

import 'package:canchaya_app/models/user.dart';
import 'package:canchaya_app/providers/auth_provider.dart';
import 'package:canchaya_app/screens/login_screen.dart';
import 'package:canchaya_app/services/api_exception.dart';

import '../fakes/fake_auth_service.dart';

Widget _wrap(AuthProvider provider) {
  return ChangeNotifierProvider.value(
    value: provider,
    child: const MaterialApp(home: LoginScreen()),
  );
}

void main() {
  group('LoginScreen - validación de formulario', () {
    testWidgets('muestra errores si los campos están vacíos', (tester) async {
      final provider = AuthProvider(authService: FakeAuthService());
      await tester.pumpWidget(_wrap(provider));

      await tester.tap(find.text('Entrar'));
      await tester.pump();

      expect(find.text('Ingresa tu correo'), findsOneWidget);
      expect(find.text('Ingresa tu contraseña'), findsOneWidget);
    });
  });

  group('LoginScreen - login fallido', () {
    testWidgets('credenciales incorrectas muestra SnackBar con el error', (
      tester,
    ) async {
      final provider = AuthProvider(
        authService: FakeAuthService(
          throwOnLogin: ApiException(
            'Las credenciales no coinciden.',
            statusCode: 401,
          ),
        ),
      );
      await tester.pumpWidget(_wrap(provider));

      await tester.enterText(
        find.widgetWithText(TextFormField, 'Correo'),
        'juan@test.com',
      );
      await tester.enterText(
        find.widgetWithText(TextFormField, 'Contraseña'),
        'malaClave',
      );

      await tester.tap(find.text('Entrar'));
      await tester.pump();
      await tester.pump();

      expect(find.text('Las credenciales no coinciden.'), findsOneWidget);
      expect(provider.status, AuthStatus.unauthenticated);
    });
  });

  group('LoginScreen - login exitoso', () {
    testWidgets('credenciales correctas actualiza el status a authenticated', (
      tester,
    ) async {
      final fakeUser = AppUser(
        id: 1,
        name: 'Juan Pérez',
        email: 'juan@test.com',
        phone: null,
        role: 'user',
      );
      final provider = AuthProvider(
        authService: FakeAuthService(userToReturn: fakeUser),
      );
      await tester.pumpWidget(_wrap(provider));

      await tester.enterText(
        find.widgetWithText(TextFormField, 'Correo'),
        'juan@test.com',
      );
      await tester.enterText(
        find.widgetWithText(TextFormField, 'Contraseña'),
        '123456',
      );

      await tester.tap(find.text('Entrar'));
      await tester.pump();

      expect(provider.status, AuthStatus.authenticated);
      expect(provider.user?.email, 'juan@test.com');
    });
  });

  group('LoginScreen - estado de carga', () {
    testWidgets('muestra spinner en el botón mientras isLoading es true', (
      tester,
    ) async {
      final fakeUser = AppUser(
        id: 1,
        name: 'Juan Pérez',
        email: 'juan@test.com',
        phone: null,
        role: 'user',
      );

      final gate = Completer<void>();
      final fakeService = FakeAuthService(userToReturn: fakeUser)..gate = gate;
      final provider = AuthProvider(authService: fakeService);

      await tester.pumpWidget(_wrap(provider));

      await tester.enterText(
        find.widgetWithText(TextFormField, 'Correo'),
        'juan@test.com',
      );
      await tester.enterText(
        find.widgetWithText(TextFormField, 'Contraseña'),
        '123456',
      );

      await tester.tap(find.text('Entrar'));
      await tester
          .pump(); // isLoading ya es true, pero login() está "congelado" en el gate

      expect(find.byType(CircularProgressIndicator), findsOneWidget);

      gate.complete(); // dejamos que login() termine
      await tester.pumpAndSettle();

      expect(provider.status, AuthStatus.authenticated);
    });
  });
}
