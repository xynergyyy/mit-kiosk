import 'package:dio/dio.dart';

class ApiClient {
  final Dio dio;

  ApiClient({
    required String baseUrl,
    String? token,
  }) : dio = Dio(
          BaseOptions(
            baseUrl: baseUrl,
            connectTimeout: const Duration(seconds: 10),
            receiveTimeout: const Duration(seconds: 15),
            headers: {
              'Accept': 'application/json',
              if (token != null) 'Authorization': 'Bearer $token',
            },
          ),
        );
}

