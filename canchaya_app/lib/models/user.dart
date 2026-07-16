class AppUser {
  final int id;
  final String name;
  final String email;
  final String? phone;
  final String role;

  AppUser({
    required this.id,
    required this.name,
    required this.email,
    this.phone,
    required this.role,
  });

  factory AppUser.fromJson(Map<String, dynamic> json) {
    return AppUser(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      phone: json['phone'],
      role: json['role'],
    );
  }

  bool get isAdmin => role == 'admin';
}
