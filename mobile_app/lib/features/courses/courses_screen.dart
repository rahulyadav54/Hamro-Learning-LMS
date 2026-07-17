import 'package:flutter/material.dart';

import '../../widgets/section_header.dart';

class CoursesScreen extends StatelessWidget {
  const CoursesScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(20),
      children: const [
        SectionHeader(
          title: 'My Courses',
          subtitle: 'Continue learning where you left off',
        ),
        SizedBox(height: 16),
        _CourseTile(
          title: 'Mathematics Class 10',
          subtitle: 'Algebra, Geometry, and practice sets',
          progress: 0.84,
        ),
        SizedBox(height: 12),
        _CourseTile(
          title: 'Science Fundamentals',
          subtitle: 'Physics, chemistry, and biology basics',
          progress: 0.62,
        ),
        SizedBox(height: 12),
        _CourseTile(
          title: 'English Grammar',
          subtitle: 'Speaking, writing, and exam prep',
          progress: 0.71,
        ),
      ],
    );
  }
}

class _CourseTile extends StatelessWidget {
  const _CourseTile({
    required this.title,
    required this.subtitle,
    required this.progress,
  });

  final String title;
  final String subtitle;
  final double progress;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              title,
              style: const TextStyle(fontWeight: FontWeight.w700),
            ),
            const SizedBox(height: 6),
            Text(subtitle),
            const SizedBox(height: 12),
            LinearProgressIndicator(value: progress),
            const SizedBox(height: 8),
            Text('${(progress * 100).round()}% completed'),
          ],
        ),
      ),
    );
  }
}
