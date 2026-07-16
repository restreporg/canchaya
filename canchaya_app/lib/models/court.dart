import 'schedule.dart';

class Court {
  final int id;
  final String name;
  final String type;
  final double pricePerHour;
  final String? location;
  final String? description;
  final bool isActive;
  final String? imageUrl;
  final List<Schedule> schedules;

  Court({
    required this.id,
    required this.name,
    required this.type,
    required this.pricePerHour,
    this.location,
    this.description,
    required this.isActive,
    this.imageUrl,
    this.schedules = const [],
  });

  factory Court.fromJson(Map<String, dynamic> json) {
    return Court(
      id: json['id'],
      name: json['name'],
      type: json['type'],
      pricePerHour: double.parse(json['price_per_hour'].toString()),
      location: json['location'],
      description: json['description'],
      isActive: json['is_active'] ?? true,
      imageUrl: json['image_url'],
      schedules: (json['schedules'] as List<dynamic>? ?? [])
          .map((s) => Schedule.fromJson(s))
          .toList(),
    );
  }
}
