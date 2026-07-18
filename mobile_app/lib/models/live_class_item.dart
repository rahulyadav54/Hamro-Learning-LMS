class LiveClassItem {
  LiveClassItem({
    required this.courseTitle,
    required this.classTitle,
    required this.startDate,
    required this.endDate,
    required this.time,
    required this.status,
    required this.instructorName,
    required this.joinUrl,
    required this.host,
  });

  final String courseTitle;
  final String classTitle;
  final String? startDate;
  final String? endDate;
  final String? time;
  final String status;
  final String instructorName;
  final String? joinUrl;
  final String? host;

  factory LiveClassItem.fromJson(Map<String, dynamic> json) {
    return LiveClassItem(
      courseTitle: json['course_title']?.toString() ?? '',
      classTitle: json['class_title']?.toString() ?? '',
      startDate: json['start_date']?.toString(),
      endDate: json['end_date']?.toString(),
      time: json['time']?.toString(),
      status: json['status']?.toString() ?? 'Scheduled',
      instructorName: json['instructor_name']?.toString() ?? '',
      joinUrl: json['join_url']?.toString(),
      host: json['host']?.toString(),
    );
  }
}
