import '../../models/course_item.dart';
import '../../models/dashboard_summary.dart';
import '../../models/live_class_item.dart';
import '../../models/purchase_item.dart';
import '../../models/student_profile.dart';
import '../constants/app_constants.dart';
import '../session/app_session.dart';
import 'api_client.dart';

class LmsApiService {
  LmsApiService._();

  static final LmsApiService instance = LmsApiService._();

  final ApiClient _client = ApiClient(baseUrl: AppConstants.apiBaseUrl);

  Future<StudentProfile> login({
    required String email,
    required String password,
  }) async {
    final response = await _client.postJson(
      '/login',
      body: <String, dynamic>{
        'email': email,
        'password': password,
      },
      authenticated: false,
    );

    if (response['success'] != true) {
      throw Exception(response['message'] ?? 'Login failed');
    }

    final tokenData = (response['data'] as Map<String, dynamic>?) ?? {};
    final token = tokenData['access_token']?.toString() ?? '';
    if (token.isEmpty) {
      throw Exception('Missing access token');
    }

    AppSession.instance.signIn(token: token);
    final profile = await fetchProfile();
    AppSession.instance.signIn(token: token, user: profile.toJson());
    return profile;
  }

  Future<StudentProfile> fetchProfile() async {
    final response = await _client.getJson('/user');

    if (response['success'] != true) {
      throw Exception(response['message'] ?? 'Failed to load profile');
    }

    final data = (response['data'] as Map<String, dynamic>?) ?? {};
    return StudentProfile.fromJson(data);
  }

  Future<DashboardSummary> fetchDashboard() async {
    final response = await _client.getJson('/dashboard');

    if (response['success'] != true) {
      throw Exception(response['message'] ?? 'Failed to load dashboard');
    }

    final data = (response['data'] as Map<String, dynamic>?) ?? {};
    return DashboardSummary.fromJson(data);
  }

  Future<List<CourseItem>> fetchMyCourses() async {
    final response = await _client.getJson('/my-courses');

    if (response['success'] != true) {
      throw Exception(response['message'] ?? 'Failed to load courses');
    }

    final list = (response['data'] as List<dynamic>?) ?? [];
    return list
        .whereType<Map<String, dynamic>>()
        .map(CourseItem.fromJson)
        .toList();
  }

  Future<List<LiveClassItem>> fetchLiveClasses() async {
    final response = await _client.getJson('/live-classes');

    if (response['success'] != true) {
      throw Exception(response['message'] ?? 'Failed to load live classes');
    }

    final list = (response['data'] as List<dynamic>?) ?? [];
    return list
        .whereType<Map<String, dynamic>>()
        .map(LiveClassItem.fromJson)
        .toList();
  }

  Future<List<PurchaseItem>> fetchPurchases() async {
    final response = await _client.getJson('/purchases');

    if (response['success'] != true) {
      throw Exception(response['message'] ?? 'Failed to load payments');
    }

    final list = (response['data'] as List<dynamic>?) ?? [];
    return list
        .whereType<Map<String, dynamic>>()
        .map(PurchaseItem.fromJson)
        .toList();
  }
}
