import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_staff_app/state/app_providers.dart';
import 'package:flutter_staff_app/ui/screens/home_shell.dart';
import 'package:flutter_staff_app/ui/screens/login_screen.dart';
import 'package:go_router/go_router.dart';

final routerProvider = Provider<GoRouter>((ref) {
  final token = ref.watch(tokenProvider);

  return GoRouter(
    initialLocation: '/app',
    redirect: (ctx, state) {
      final loggedIn = token.valueOrNull != null;
      final atLogin = state.matchedLocation == '/login';
      if (!loggedIn && !atLogin) return '/login';
      if (loggedIn && atLogin) return '/app';
      return null;
    },
    routes: [
      GoRoute(
        path: '/login',
        builder: (ctx, _) => const LoginScreen(),
      ),
      GoRoute(
        path: '/app',
        builder: (ctx, _) => const HomeShell(),
      ),
    ],
  );
});
