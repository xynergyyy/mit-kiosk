import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_staff_app/api/mobile_api.dart';
import 'package:flutter_staff_app/models/appointment_request.dart';
import 'package:flutter_staff_app/state/app_providers.dart';

final appointmentsControllerProvider = AsyncNotifierProvider<
    AppointmentsController, List<AppointmentRequest>>(AppointmentsController.new);

class AppointmentsController extends AsyncNotifier<List<AppointmentRequest>> {
  MobileApi get _api => ref.read(mobileApiProvider);

  @override
  Future<List<AppointmentRequest>> build() async {
    return _api.listAppointments();
  }

  Future<void> refresh() async {
    state = const AsyncLoading();
    state = await AsyncValue.guard(() => _api.listAppointments());
  }

  Future<void> refreshSilently() async {
    final next = await AsyncValue.guard(() => _api.listAppointments());
    if (!next.hasError) {
      state = next;
    }
  }

  Future<void> accept(int id, DateTime scheduledAt) async {
    final prev = state.valueOrNull ?? const <AppointmentRequest>[];
    state = const AsyncLoading();
    final next = await AsyncValue.guard(() async {
      await _api.acceptAppointment(id: id, scheduledAt: scheduledAt);
      return _api.listAppointments();
    });
    state = next;
    if (next.hasError) {
      state = AsyncValue.data(prev);
      throw next.asError!.error;
    }
  }

  Future<void> decline(int id) async {
    final prev = state.valueOrNull ?? const <AppointmentRequest>[];
    state = const AsyncLoading();
    final next = await AsyncValue.guard(() async {
      await _api.declineAppointment(id: id);
      return _api.listAppointments();
    });
    state = next;
    if (next.hasError) {
      state = AsyncValue.data(prev);
      throw next.asError!.error;
    }
  }
}
