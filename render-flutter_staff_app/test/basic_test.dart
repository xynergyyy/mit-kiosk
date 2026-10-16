import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_staff_app/models/consultation.dart';

void main() {
  test('Consultation ticket number pads to 3 digits', () {
    final c = Consultation.fromJson({
      'id': 1,
      'assigned_user_id': 1,
      'waypoint_id': null,
      'waypoint_name': null,
      'ticket_number': 7,
      'purpose_code': 'Test',
      'purpose_other_text': null,
      'status': 'waiting',
      'created_at': '2026-01-01T00:00:00Z',
    });
    expect(c.ticketNumberPadded, '007');
  });
}
