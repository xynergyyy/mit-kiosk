class AppConfig {
  static String apiBaseUrl = const String.fromEnvironment(
    'API_BASE_URL',
    defaultValue: 'https://laravel-web-app-msri.onrender.com',
  );

  static void setBaseUrl(String url) {
    apiBaseUrl = url.trim().replaceAll(RegExp(r'/+$'), '');
  }
}

