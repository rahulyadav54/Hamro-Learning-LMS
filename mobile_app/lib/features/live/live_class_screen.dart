import 'package:flutter/material.dart';

import '../../core/network/lms_api_service.dart';
import '../../core/utils/link_launcher.dart';
import '../../models/live_class_item.dart';
import '../../widgets/section_header.dart';

class LiveClassScreen extends StatefulWidget {
  const LiveClassScreen({super.key});

  @override
  State<LiveClassScreen> createState() => _LiveClassScreenState();
}

class _LiveClassScreenState extends State<LiveClassScreen> {
  late Future<List<LiveClassItem>> _futureLiveClasses;

  @override
  void initState() {
    super.initState();
    _futureLiveClasses = LmsApiService.instance.fetchLiveClasses();
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<List<LiveClassItem>>(
      future: _futureLiveClasses,
      builder: (context, snapshot) {
        return ListView(
          padding: const EdgeInsets.all(20),
          children: [
            const SectionHeader(
              title: 'Live Classes',
              subtitle: 'Join scheduled classes and stay updated',
            ),
            const SizedBox(height: 16),
            if (snapshot.connectionState == ConnectionState.waiting)
              const Center(child: CircularProgressIndicator())
            else if (snapshot.hasError)
              Text('Failed to load live classes: ${snapshot.error}')
            else if ((snapshot.data ?? []).isEmpty)
              const Text('No live classes available right now.')
            else
              ...snapshot.data!.map(
                (liveClass) => Padding(
                  padding: const EdgeInsets.only(bottom: 12),
                  child: Card(
                    child: Padding(
                      padding: const EdgeInsets.all(16),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            liveClass.classTitle,
                            style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w700),
                          ),
                          const SizedBox(height: 6),
                          Text(liveClass.courseTitle),
                          const SizedBox(height: 6),
                          Text(
                            '${liveClass.startDate ?? 'TBA'} ${liveClass.time != null ? ' - ${liveClass.time}' : ''}',
                          ),
                          const SizedBox(height: 6),
                          Text(
                            liveClass.status,
                            style: TextStyle(color: Theme.of(context).colorScheme.primary),
                          ),
                          const SizedBox(height: 12),
                          FilledButton(
                            onPressed: liveClass.joinUrl == null
                                ? null
                                : () => openExternalLink(liveClass.joinUrl),
                            child: const Text('Join'),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              ),
          ],
        );
      },
    );
  }
}
