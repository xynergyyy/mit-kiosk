import 'package:flutter/material.dart';

class StatusChip extends StatelessWidget {
  final String label;

  const StatusChip({
    super.key,
    required this.label,
  });

  @override
  Widget build(BuildContext context) {
    final l = label.toLowerCase();
    Color bg;
    Color fg;

    if (l.contains('pending') || l.contains('waiting')) {
      bg = const Color(0xFFFFF7ED);
      fg = const Color(0xFF9A3412);
    } else if (l.contains('accepted') || l.contains('in_progress')) {
      bg = const Color(0xFFEFF6FF);
      fg = const Color(0xFF1D4ED8);
    } else if (l.contains('declined') || l.contains('cancel') || l.contains('no_show')) {
      bg = const Color(0xFFFEF2F2);
      fg = const Color(0xFFB91C1C);
    } else if (l.contains('completed') || l.contains('done') || l.contains('served')) {
      bg = const Color(0xFFECFDF5);
      fg = const Color(0xFF047857);
    } else {
      bg = const Color(0xFFF1F5F9);
      fg = const Color(0xFF334155);
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: fg,
          fontWeight: FontWeight.w700,
          fontSize: 12,
          letterSpacing: 0.4,
        ),
      ),
    );
  }
}

