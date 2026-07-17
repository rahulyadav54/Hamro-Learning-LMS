class StudentProfile {
  StudentProfile({
    required this.id,
    required this.name,
    required this.email,
    required this.phone,
    required this.image,
  });

  final int? id;
  final String name;
  final String email;
  final String phone;
  final String image;

  factory StudentProfile.fromJson(Map<String, dynamic> json) {
    return StudentProfile(
      id: json['id'] as int?,
      name: json['name']?.toString() ?? '',
      email: json['email']?.toString() ?? '',
      phone: json['phone']?.toString() ?? '',
      image: json['image']?.toString() ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'phone': phone,
      'image': image,
    };
  }
}
