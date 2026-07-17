class CourseItem {
  CourseItem({
    required this.id,
    required this.title,
    required this.thumbnail,
    required this.duration,
    required this.instructorName,
    required this.price,
    required this.progress,
  });

  final int? id;
  final String title;
  final String thumbnail;
  final String duration;
  final String instructorName;
  final double price;
  final int progress;

  factory CourseItem.fromJson(Map<String, dynamic> json) {
    return CourseItem(
      id: json['course_id'] as int? ?? json['id'] as int?,
      title: json['title']?.toString() ?? '',
      thumbnail: json['thumbnail']?.toString() ?? '',
      duration: json['duration']?.toString() ?? '',
      instructorName: json['instructor_name']?.toString() ?? json['userName']?.toString() ?? '',
      price: (json['price'] as num?)?.toDouble() ?? 0,
      progress: (json['progress'] as num?)?.toInt() ?? 0,
    );
  }
}
