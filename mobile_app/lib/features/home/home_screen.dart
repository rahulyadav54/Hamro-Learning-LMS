import 'package:flutter/material.dart';

import '../../core/network/lms_api_service.dart';
import '../../core/utils/meeting_launcher.dart';
import '../../models/course_item.dart';
import '../../models/dashboard_summary.dart';
import '../../widgets/section_header.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  late Future<DashboardSummary> _futureSummary;

  @override
  void initState() {
    super.initState();
    _futureSummary = LmsApiService.instance.fetchDashboard();
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<DashboardSummary>(
      future: _futureSummary,
      builder: (context, snapshot) {
        final summary = snapshot.data;
        final userName = summary == null ? 'Student' : summary.user.name;
        final recentCourses = summary?.recentCourses ?? const <CourseItem>[];

        return ListView(
          padding: const EdgeInsets.all(20),
          children: [
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Color(0xFF1E3A8A), Color(0xFF0F766E)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: BorderRadius.circular(24),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Welcome back, $userName',
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 24,
                      fontWeight: FontWeight.w800,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Your learning space is ready.',
                    style: TextStyle(color: Colors.white.withOpacity(0.8)),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),
            const SectionHeader(
              title: 'Quick Overview',
              subtitle: 'Everything you need at a glance',
            ),
            const SizedBox(height: 12),
            if (snapshot.connectionState == ConnectionState.waiting)
              const Center(child: CircularProgressIndicator())
            else if (snapshot.hasError)
              Text('Failed to load dashboard: ${snapshot.error}')
            else ...[
              Row(
                children: [
                  Expanded(
                    child: _StatCard(
                      title: 'Courses',
                      value: '${summary?.enrolledCourses ?? 0}',
                      icon: Icons.menu_book,
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: _StatCard(
                      title: 'Live today',
                      value: '${summary?.liveClasses ?? 0}',
                      icon: Icons.videocam,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),
              Row(
                children: [
                  Expanded(
                    child: _StatCard(
                      title: 'Pending',
                      value: '${summary?.pendingPayments ?? 0}',
                      icon: Icons.payments,
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: _StatCard(
                      title: 'Spent',
                      value: 'INR ${(summary?.totalSpent ?? 0).toStringAsFixed(0)}',
                      icon: Icons.trending_up,
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 24),
              const SectionHeader(
                title: 'Next Live Class',
                subtitle: 'Join your upcoming class in one tap',
              ),
              const SizedBox(height: 12),
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        summary?.nextLiveClass?.classTitle ?? 'No live class scheduled',
                        style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w700),
                      ),
                      const SizedBox(height: 6),
                      Text(
                        summary?.nextLiveClass?.courseTitle ?? 'We will show your next session here.',
                      ),
                      const SizedBox(height: 12),
                      FilledButton(
                        onPressed: summary?.nextLiveClass?.joinUrl == null
                            ? null
                            : () => openMeeting(
                                  context,
                                  summary?.nextLiveClass?.joinUrl,
                                  summary?.nextLiveClass?.classTitle ?? 'Live Class',
                                ),
                        child: const Text('Join Live Class'),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 24),
              const SectionHeader(
                title: 'Recent Activity',
                subtitle: 'Your latest learning updates',
              ),
              const SizedBox(height: 12),
              ...recentCourses.take(3).map(
                    (course) => _ActivityItem(
                      title: course.title,
                      subtitle: '${course.progress}% completed',
                    ),
                  ),
              if (recentCourses.isEmpty)
                const Text('No recent learning activity yet.'),
            ],
          ],
        );
      },
    );
  }
}

class _StatCard extends StatelessWidget {
  const _StatCard({
    required this.title,
    required this.value,
    required this.icon,
  });

  final String title;
  final String value;
  final IconData icon;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Icon(icon, color: Theme.of(context).colorScheme.primary),
            const SizedBox(height: 12),
            Text(
              value,
              style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w800),
            ),
            const SizedBox(height: 4),
            Text(title, style: const TextStyle(color: Colors.black54)),
          ],
        ),
      ),
    );
  }
}

class _ActivityItem extends StatelessWidget {
  const _ActivityItem({
    required this.title,
    required this.subtitle,
  });

  final String title;
  final String subtitle;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: ListTile(
        leading: const CircleAvatar(child: Icon(Icons.notifications)),
        title: Text(title, style: const TextStyle(fontWeight: FontWeight.w700)),
        subtitle: Text(subtitle),
      ),
    );
  }
}
