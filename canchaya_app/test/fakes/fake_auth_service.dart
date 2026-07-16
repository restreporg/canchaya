import 'dart:async';

import 'package:canchaya_app/models/user.dart';
import 'package:canchaya_app/services/api_exception.dart';
import 'package:canchaya_app/services/auth_service.dart';

/// Doble de prueba para AuthService: no toca red ni storage real.
/// Se configura antes de cada test para simular el escenario que quieras.
class FakeAuthService implements AuthService {
  FakeAuthService({this.userToReturn, this.throwOnLogin, this.throwOnRegister});

  AppUser? userToReturn;
  ApiException? throwOnLogin;
  ApiException? throwOnRegister;

  /// Si se asigna, login()/register() esperan a que este Completer se
  /// complete antes de devolver el resultado. Útil para probar estados
  /// de carga (isLoading = true) de forma controlada en los tests.
  Completer<void>? gate;

  @override
  Future<AppUser?> getCurrentUser() async => userToReturn;

  @override
  Future<AppUser> login({
    required String email,
    required String password,
  }) async {
    if (gate != null) await gate!.future;
    if (throwOnLogin != null) throw throwOnLogin!;
    return userToReturn!;
  }

  @override
  Future<AppUser> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String? phone,
  }) async {
    if (gate != null) await gate!.future;
    if (throwOnRegister != null) throw throwOnRegister!;
    return userToReturn!;
  }

  @override
  Future<void> logout() async {
    userToReturn = null;
  }
}
