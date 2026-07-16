import 'package:dio/dio.dart';
import '../models/reservation.dart';
import 'api_client.dart';

class ReservationService {
  final _client = ApiClient();

  Future<List<Reservation>> getMyReservations() async {
    try {
      final res = await _client.dio.get('/client/reservations');
      final List<dynamic> data = res.data['data'];
      return data.map((r) => Reservation.fromJson(r)).toList();
    } on DioException catch (e) {
      throw _client.handleError(e);
    }
  }

  /// [start] y [end] deben ir en formato "yyyy-MM-dd HH:mm:ss".
  Future<Reservation> createReservation({
    required int courtId,
    required String start,
    required String end,
  }) async {
    try {
      final res = await _client.dio.post('/client/reservations', data: {
        'court_id': courtId,
        'start_datetime': start,
        'end_datetime': end,
      });
      return Reservation.fromJson(res.data['data']);
    } on DioException catch (e) {
      throw _client.handleError(e);
    }
  }

  Future<void> cancelReservation(int id) async {
    try {
      await _client.dio.delete('/client/reservations/$id');
    } on DioException catch (e) {
      throw _client.handleError(e);
    }
  }
}
