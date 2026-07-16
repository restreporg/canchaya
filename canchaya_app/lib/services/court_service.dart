import 'package:dio/dio.dart';
import '../models/court.dart';
import 'api_client.dart';

class CourtService {
  final _client = ApiClient();

  /// GET /courts (público). Laravel devuelve {"data": [...], "links":..., "meta":...}
  /// por la paginación, así que leemos res.data['data'].
  Future<List<Court>> getCourts({String? type, String? location}) async {
    try {
      final res = await _client.dio.get('/courts', queryParameters: {
        if (type != null) 'type': type,
        if (location != null) 'location': location,
      });

      final List<dynamic> data = res.data['data'];
      return data.map((c) => Court.fromJson(c)).toList();
    } on DioException catch (e) {
      throw _client.handleError(e);
    }
  }

  Future<Court> getCourt(int id) async {
    try {
      final res = await _client.dio.get('/courts/$id');
      return Court.fromJson(res.data['data']);
    } on DioException catch (e) {
      throw _client.handleError(e);
    }
  }
}
