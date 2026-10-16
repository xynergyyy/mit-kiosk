import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class TokenStorage {
  static const _key = 'api_token';
  static const _serverUrlKey = 'custom_server_url';
  final FlutterSecureStorage _storage;

  const TokenStorage(this._storage);

  Future<String?> readToken() => _storage.read(key: _key);

  Future<void> writeToken(String token) => _storage.write(key: _key, value: token);

  Future<void> clearToken() => _storage.delete(key: _key);

  Future<String?> readServerUrl() => _storage.read(key: _serverUrlKey);

  Future<void> writeServerUrl(String url) => _storage.write(key: _serverUrlKey, value: url);
}

