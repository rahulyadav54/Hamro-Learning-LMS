import 'course_item.dart';
import 'live_class_item.dart';
import 'purchase_item.dart';
import 'student_profile.dart';

class DashboardSummary {
  DashboardSummary({
    required this.user,
    required this.enrolledCourses,
    required this.liveClasses,
    required this.totalSpent,
    required this.pendingPayments,
    required this.nextLiveClass,
    required this.recentCourses,
    required this.recentPayments,
  });

  final StudentProfile user;
  final int enrolledCourses;
  final int liveClasses;
  final double totalSpent;
  final int pendingPayments;
  final LiveClassItem? nextLiveClass;
  final List<CourseItem> recentCourses;
  final List<PurchaseItem> recentPayments;

  factory DashboardSummary.fromJson(Map<String, dynamic> json) {
    final stats = (json['stats'] as Map<String, dynamic>?) ?? {};

    final recentCourses = ((json['recent_courses'] as List<dynamic>?) ?? [])
        .whereType<Map<String, dynamic>>()
        .map(CourseItem.fromJson)
        .toList();

    final recentPayments = ((json['recent_payments'] as List<dynamic>?) ?? [])
        .whereType<Map<String, dynamic>>()
        .map(PurchaseItem.fromJson)
        .toList();

    final nextLiveClassData = json['next_live_class'];
    final nextLiveClass = nextLiveClassData is Map<String, dynamic>
        ? LiveClassItem.fromJson(nextLiveClassData)
        : null;

    return DashboardSummary(
      user: StudentProfile.fromJson((json['user'] as Map<String, dynamic>?) ?? {}),
      enrolledCourses: (stats['enrolled_courses'] as num?)?.toInt() ?? 0,
      liveClasses: (stats['live_classes'] as num?)?.toInt() ?? 0,
      totalSpent: (stats['total_spent'] as num?)?.toDouble() ?? 0,
      pendingPayments: (stats['pending_payments'] as num?)?.toInt() ?? 0,
      nextLiveClass: nextLiveClass,
      recentCourses: recentCourses,
      recentPayments: recentPayments,
    );
  }
}
