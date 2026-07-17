class AppSession {
  AppSession._();

  static final AppSession instance = AppSession._();

  String? token;
  Map<String, dynamic>? user;

  bool get isLoggedIn => token != null && token!.isNotEmpty;

  void signIn({
    required String token,
    Map<String, dynamic>? user,
  }) {
    this.token = token;
    this.user = user;
  }

  void signOut() {
    token = null;
    user = null;
  }
}
