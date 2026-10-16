import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:flutter_staff_app/api/api_client.dart';
import 'package:flutter_staff_app/api/mobile_api.dart';
import 'package:flutter_staff_app/config.dart';
import 'package:flutter_staff_app/models/user_me.dart';
import 'package:flutter_staff_app/storage/token_storage.dart';

final secureStorageProvider = Provider<FlutterSecureStorage>((ref) {
  return const FlutterSecureStorage();
});

final tokenStorageProvider = Provider<TokenStorage>((ref) {
  return TokenStorage(ref.watch(secureStorageProvider));
});

final tokenProvider = FutureProvider<String?>((ref) async {
  final storage = ref.watch(tokenStorageProvider);
  return storage.readToken();
});

final dioProvider = Provider<Dio>((ref) {
  final tokenAsync = ref.watch(tokenProvider);
  final token = tokenAsync.value;
  return ApiClient(baseUrl: AppConfig.apiBaseUrl, token: token).dio;
});

final mobileApiProvider = Provider<MobileApi>((ref) {
  return MobileApi(ref.watch(dioProvider));
});

final meProvider = FutureProvider<UserMe>((ref) async {
  final api = ref.watch(mobileApiProvider);
  return api.me();
});

