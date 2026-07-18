import 'package:flutter/material.dart';

import '../../core/network/lms_api_service.dart';
import '../../core/utils/meeting_launcher.dart';
import '../../models/course_item.dart';
import '../../widgets/section_header.dart';

class ClassesScreen extends StatefulWidget {
  const ClassesScreen({super.key});

  @override
  State<ClassesScreen> createState() => _ClassesScreenState();
}

class _ClassesScreenState extends State<ClassesScreen> {
  late Future<List<CourseItem>> _futureCourses;

  @override
  void initState() {
    super.initState();
    _futureCourses = LmsApiService.instance.fetchMyCourses();
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<List<CourseItem>>(
      future: _futureCourses,
      builder: (context, snapshot) {
        return ListView(
          padding: const EdgeInsets.all(20),
          children: [
            const SectionHeader(
              title: 'My Classes',
              subtitle: 'Track enrolled classes and progress',
            ),
            const SizedBox(height: 16),
            if (snapshot.connectionState == ConnectionState.waiting)
              const Center(child: CircularProgressIndicator())
            else if (snapshot.hasError)
              Text('Failed to load classes: ${snapshot.error}')
            else ...[
              for (final course in snapshot.data ?? <CourseItem>[])
                Padding(
                  padding: const EdgeInsets.only(bottom: 12),
                  child: Card(
                    child: Padding(
                      padding: const EdgeInsets.all(16),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            course.title,
                            style: const TextStyle(fontWeight: FontWeight.w700),
                          ),
                          const SizedBox(height: 6),
                          Text(course.instructorName),
                          const SizedBox(height: 12),
                          LinearProgressIndicator(
                            value: course.progress / 100,
                          ),
                          const SizedBox(height: 8),
                          Text('${course.progress}% completed'),
                          if (course.joinUrl != null) ...[
                            const SizedBox(height: 12),
                            FilledButton(
                              onPressed: () => openMeeting(
                                context,
                                course.joinUrl,
                                course.title,
                              ),
                              child: const Text('Join Class'),
                            ),
                          ],
                        ],
                      ),
                    ),
                  ),
                ),
              if ((snapshot.data ?? []).isEmpty)
                const Text('No enrolled classes found.'),
            ],
          ],
        );
      },
    );
  }
}
