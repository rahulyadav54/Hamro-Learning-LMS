import 'package:flutter/material.dart';

import 'core/theme/app_theme.dart';
import 'features/auth/login_screen.dart';

class HamroLearningApp extends StatelessWidget {
  const HamroLearningApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Hamro Learning',
      theme: AppTheme.light(),
      routes: {
        '/login': (_) => const LoginScreen(),
      },
      home: const LoginScreen(),
    );
  }
}
