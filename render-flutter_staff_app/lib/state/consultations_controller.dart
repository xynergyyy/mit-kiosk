import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_staff_app/api/mobile_api.dart';
import 'package:flutter_staff_app/models/consultation.dart';
import 'package:flutter_staff_app/state/app_providers.dart';

final consultationsControllerProvider = AsyncNotifierProvider<
    ConsultationsController, List<Consultation>>(ConsultationsController.new);

class ConsultationsController extends AsyncNotifier<List<Consultation>> {
  MobileApi get _api => ref.read(mobileApiProvider);

  @override
  Future<List<Consultation>> build() async {
    return _api.listConsultations();
  }

  Future<void> refresh() async {
    state = const AsyncLoading();
    state = await AsyncValue.guard(() => _api.listConsultations());
  }

  Future<void> refreshSilently() async {
    final next = await AsyncValue.guard(() => _api.listConsultations());
    if (!next.hasError) {
      state = next;
    }
  }

  Future<void> start(int id) async {
    final prev = state.valueOrNull ?? const <Consultation>[];
    state = const AsyncLoading();
    final next = await AsyncValue.guard(() async {
      await _api.startConsultation(id: id);
      return _api.listConsultations();
    });
    state = next;
    if (next.hasError) {
      state = AsyncValue.data(prev);
      throw next.asError!.error;
    }
  }

  Future<void> finish(int id) async {
    final prev = state.valueOrNull ?? const <Consultation>[];
    state = const AsyncLoading();
    final next = await AsyncValue.guard(() async {
      await _api.finishConsultation(id: id);
      return _api.listConsultations();
    });
    state = next;
    if (next.hasError) {
      state = AsyncValue.data(prev);
      throw next.asError!.error;
    }
  }
}
