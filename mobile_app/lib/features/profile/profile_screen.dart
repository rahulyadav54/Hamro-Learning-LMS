import 'package:flutter/material.dart';

import '../../core/network/lms_api_service.dart';
import '../../core/session/app_session.dart';
import '../../models/student_profile.dart';
import '../../widgets/section_header.dart';
import '../auth/login_screen.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  late Future<StudentProfile> _futureProfile;

  @override
  void initState() {
    super.initState();
    if (AppSession.instance.user != null) {
      _futureProfile = Future.value(StudentProfile.fromJson(AppSession.instance.user!));
    } else {
      _futureProfile = LmsApiService.instance.fetchProfile();
    }
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<StudentProfile>(
      future: _futureProfile,
      builder: (context, snapshot) {
        final profile = snapshot.data;
        final hasName = profile != null && profile.name.isNotEmpty;

        return ListView(
          padding: const EdgeInsets.all(20),
          children: [
            const SectionHeader(
              title: 'My Profile',
              subtitle: 'Student account information and settings',
            ),
            const SizedBox(height: 16),
            if (snapshot.connectionState == ConnectionState.waiting)
              const Center(child: CircularProgressIndicator())
            else if (snapshot.hasError)
              Text('Failed to load profile: ${snapshot.error}')
            else ...[
              Card(
                child: ListTile(
                  leading: CircleAvatar(
                    child: Text(
                      hasName
                          ? profile!.name[0].toUpperCase()
                          : 'S',
                    ),
                  ),
                  title: Text(profile?.name ?? 'Student'),
                  subtitle: Text(profile?.email ?? ''),
                ),
              ),
              const SizedBox(height: 12),
              _ProfileAction(title: 'Edit Profile', icon: Icons.edit, onTap: () {}),
              _ProfileAction(title: 'Change Password', icon: Icons.lock, onTap: () {}),
              _ProfileAction(title: 'Notification Settings', icon: Icons.notifications, onTap: () {}),
              _ProfileAction(
                title: 'Logout',
                icon: Icons.logout,
                onTap: () {
                  AppSession.instance.signOut();
                  Navigator.of(context).pushAndRemoveUntil(
                    MaterialPageRoute<void>(builder: (_) => const LoginScreen()),
                    (route) => false,
                  );
                },
              ),
            ],
          ],
        );
      },
    );
  }
}

class _ProfileAction extends StatelessWidget {
  const _ProfileAction({
    required this.title,
    required this.icon,
    required this.onTap,
  });

  final String title;
  final IconData icon;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: ListTile(
        leading: Icon(icon),
        title: Text(title),
        trailing: const Icon(Icons.chevron_right),
        onTap: onTap,
      ),
    );
  }
}
