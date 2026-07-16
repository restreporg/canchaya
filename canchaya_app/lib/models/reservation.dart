import 'court.dart';

class Reservation {
  final int id;
  final String status; // pendiente, confirmada, completada, cancelada
  final DateTime startDatetime;
  final DateTime endDatetime;
  final double totalPrice;
  final Court? court;

  Reservation({
    required this.id,
    required this.status,
    required this.startDatetime,
    required this.endDatetime,
    required this.totalPrice,
    this.court,
  });

  factory Reservation.fromJson(Map<String, dynamic> json) {
    return Reservation(
      id: json['id'],
      status: json['status'],
      startDatetime: DateTime.parse(json['start_datetime']),
      endDatetime: DateTime.parse(json['end_datetime']),
      totalPrice: double.parse(json['total_price'].toString()),
      court: json['court'] != null ? Court.fromJson(json['court']) : null,
    );
  }
}
