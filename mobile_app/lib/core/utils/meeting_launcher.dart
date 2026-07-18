import 'package:flutter/material.dart';

import '../../features/live/live_meeting_screen.dart';

Future<void> openMeeting(BuildContext context, String? url, String title) async {
  if (url == null || url.isEmpty) {
    return;
  }

  await Navigator.of(context).push(
    MaterialPageRoute<void>(
      builder: (_) => LiveMeetingScreen(
        url: url,
        title: title,
      ),
    ),
  );
}
