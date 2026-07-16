class Schedule {
  final int id;
  final int courtId;
  final int dayOfWeek; // 0 = domingo ... 6 = sábado
  final String openTime;
  final String closeTime;
  final bool isAvailable;

  Schedule({
    required this.id,
    required this.courtId,
    required this.dayOfWeek,
    required this.openTime,
    required this.closeTime,
    required this.isAvailable,
  });

  factory Schedule.fromJson(Map<String, dynamic> json) {
    return Schedule(
      id: json['id'],
      courtId: json['court_id'],
      dayOfWeek: json['day_of_week'],
      openTime: json['open_time'],
      closeTime: json['close_time'],
      isAvailable: json['is_available'] ?? true,
    );
  }
}
