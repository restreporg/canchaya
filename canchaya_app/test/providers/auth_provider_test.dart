import 'package:flutter_test/flutter_test.dart';
import 'package:canchaya_app/models/user.dart';
import 'package:canchaya_app/providers/auth_provider.dart';
import 'package:canchaya_app/services/api_exception.dart';

import '../fakes/fake_auth_service.dart';

void main() {
  final fakeUser = AppUser(
    id: 1,
    name: 'Juan Pérez',
    email: 'juan@test.com',
    phone: '3001234567',
    role: 'user',
  );

  group('tryAutoLogin', () {
    test('sin sesión previa -> status unauthenticated', () async {
      final provider = AuthProvider(
        authService: FakeAuthService(userToReturn: null),
      );

      await provider.tryAutoLogin();

      expect(provider.status, AuthStatus.unauthenticated);
      expect(provider.user, isNull);
    });

    test('con sesión previa -> status authenticated', () async {
      final provider = AuthProvider(
        authService: FakeAuthService(userToReturn: fakeUser),
      );

      await provider.tryAutoLogin();

      expect(provider.status, AuthStatus.authenticated);
      expect(provider.user?.email, 'juan@test.com');
    });
  });

  group('login', () {
    test('credenciales correctas -> authenticated y devuelve true', () async {
      final provider = AuthProvider(
        authService: FakeAuthService(userToReturn: fakeUser),
      );

      final ok = await provider.login('juan@test.com', '123456');

      expect(ok, true);
      expect(provider.status, AuthStatus.authenticated);
      expect(provider.isLoading, false);
      expect(provider.errorMessage, isNull);
    });

    test(
      'credenciales incorrectas -> unauthenticated y devuelve false',
      () async {
        final provider = AuthProvider(
          authService: FakeAuthService(
            throwOnLogin: ApiException(
              'Las credenciales no coinciden.',
              statusCode: 401,
            ),
          ),
        );

        final ok = await provider.login('juan@test.com', 'malaClave');

        expect(ok, false);
        expect(provider.status, AuthStatus.unauthenticated);
        expect(provider.isLoading, false);
        expect(provider.errorMessage, 'Las credenciales no coinciden.');
      },
    );

    test(
      'error de validación -> errorMessage toma el primer error de campo',
      () async {
        final provider = AuthProvider(
          authService: FakeAuthService(
            throwOnLogin: ApiException(
              'Error de validación',
              statusCode: 422,
              errors: {
                'email': ['El correo no es válido.'],
              },
            ),
          ),
        );

        await provider.login('correo-invalido', '123456');

        expect(provider.errorMessage, 'El correo no es válido.');
      },
    );
  });

  group('logout', () {
    test('limpia el usuario y pone status en unauthenticated', () async {
      final provider = AuthProvider(
        authService: FakeAuthService(userToReturn: fakeUser),
      );
      await provider.tryAutoLogin(); // queda authenticated

      await provider.logout();

      expect(provider.status, AuthStatus.unauthenticated);
      expect(provider.user, isNull);
    });
  });
}
