import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../config/api_config.dart';
import '../models/court.dart';
import '../services/api_exception.dart';
import '../services/court_service.dart';
import '../services/reservation_service.dart';

class CourtDetailScreen extends StatefulWidget {
  final int courtId;
  const CourtDetailScreen({super.key, required this.courtId});

  @override
  State<CourtDetailScreen> createState() => _CourtDetailScreenState();
}

class _CourtDetailScreenState extends State<CourtDetailScreen> {
  static const _brandColor = Color(0xFF059669);
  static const _openHour = 8; // 8:00 AM
  static const _closeHour = 22; // 10:00 PM

  final _courtService = CourtService();
  final _reservationService = ReservationService();

  late Future<Court> _futureCourt;
  DateTime? _selectedDate;
  TimeOfDay? _startTime;
  TimeOfDay? _endTime;
  bool _submitting = false;

  @override
  void initState() {
    super.initState();
    _futureCourt = _courtService.getCourt(widget.courtId);
  }

  // ---------- Lógica de fecha / hora ----------

  DateTime _combine(DateTime date, TimeOfDay time) =>
      DateTime(date.year, date.month, date.day, time.hour, time.minute);

  bool _isPast(TimeOfDay time) {
    final now = DateTime.now();
    if (_selectedDate == null ||
        !(_selectedDate!.year == now.year &&
            _selectedDate!.month == now.month &&
            _selectedDate!.day == now.day)) {
      return false;
    }
    return _combine(_selectedDate!, time).isBefore(now);
  }

  List<TimeOfDay> _allSlots() => [
    for (int h = _openHour; h < _closeHour; h++) ...[
      TimeOfDay(hour: h, minute: 0),
      TimeOfDay(hour: h, minute: 30),
    ],
    TimeOfDay(hour: _closeHour, minute: 0),
  ];

  List<TimeOfDay> _slotsInRange(int fromHour, int toHour) =>
      _allSlots().where((t) => t.hour >= fromHour && t.hour < toHour).toList();

  // Franjas del día para agrupar los horarios en la hoja de selección.
  // El límite superior de "Noche" (23) es exclusivo, así cubre hasta las 22:00.
  static const _periods = [
    ('Mañana', Icons.wb_twilight, 8, 12),
    ('Tarde', Icons.wb_sunny_outlined, 12, 18),
    ('Noche', Icons.nights_stay_outlined, 18, 23),
  ];

  Future<void> _pickDate() async {
    final now = DateTime.now();
    final date = await showDatePicker(
      context: context,
      initialDate: now,
      firstDate: now,
      lastDate: now.add(const Duration(days: 90)),
      helpText: 'Selecciona una fecha',
      cancelText: 'Cancelar',
      confirmText: 'Confirmar',
      builder: (context, child) => _themedPicker(context, child),
    );
    if (date != null) {
      setState(() {
        _selectedDate = date;
        _startTime = null;
        _endTime = null;
      });
    }
  }

  Future<void> _pickTime(bool isStart) async {
    if (_selectedDate == null) {
      ScaffoldMessenger.of(
        context,
      ).showSnackBar(const SnackBar(content: Text('Primero elige una fecha.')));
      return;
    }

    await showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (_) => _TimeSlotSheet(
        isStart: isStart,
        selectedDate: _selectedDate!,
        startTime: _startTime,
        endTime: _endTime,
        brandColor: _brandColor,
        periods: _periods,
        slotsInRange: _slotsInRange,
        isPast: _isPast,
        onSelected: (slot) => setState(() {
          if (isStart) {
            _startTime = slot;
            if (_endTime != null && !_endTime!.after(slot)) _endTime = null;
          } else {
            _endTime = slot;
          }
        }),
      ),
    );
  }

  Widget _themedPicker(BuildContext context, Widget? child) => Theme(
    data: Theme.of(context).copyWith(
      colorScheme: Theme.of(context).colorScheme.copyWith(
        primary: _brandColor,
        onPrimary: Colors.white,
        secondaryContainer: _brandColor.withValues(alpha: 0.15),
        onSecondaryContainer: _brandColor,
      ),
      dialogTheme: const DialogThemeData(
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.all(Radius.circular(20)),
        ),
      ),
      textButtonTheme: TextButtonThemeData(
        style: TextButton.styleFrom(foregroundColor: _brandColor),
      ),
    ),
    child: child!,
  );

  Future<void> _confirmReservation(int courtId) async {
    if (_selectedDate == null || _startTime == null || _endTime == null) {
      _showMessage('Selecciona fecha, hora de inicio y hora de fin.');
      return;
    }

    final start = _combine(_selectedDate!, _startTime!);
    final end = _combine(_selectedDate!, _endTime!);

    if (!end.isAfter(start)) {
      _showMessage('La hora de fin debe ser posterior a la hora de inicio.');
      return;
    }
    if (start.isBefore(DateTime.now())) {
      _showMessage('No puedes reservar un horario que ya pasó.');
      return;
    }

    setState(() => _submitting = true);
    try {
      final formatter = DateFormat('yyyy-MM-dd HH:mm:ss');
      await _reservationService.createReservation(
        courtId: courtId,
        start: formatter.format(start),
        end: formatter.format(end),
      );
      if (mounted) {
        _showMessage('¡Reserva creada! Puedes verla en "Mis reservas".');
        Navigator.of(context).pop();
      }
    } on ApiException catch (e) {
      if (mounted) _showMessage(e.firstFieldError);
    } finally {
      if (mounted) setState(() => _submitting = false);
    }
  }

  void _showMessage(String text) {
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(text)));
  }

  // ---------- Estilo por deporte (igual que en courts_screen.dart) ----------

  (Color bg, Color accent, IconData icon) _sportStyle(String type) {
    switch (type.toLowerCase()) {
      case 'futbol':
      case 'fútbol':
        return (
          const Color(0xFFD1FAE5),
          const Color(0xFF059669),
          Icons.sports_soccer,
        );
      case 'tenis':
        return (
          const Color(0xFFFEF3C7),
          const Color(0xFFD97706),
          Icons.sports_tennis,
        );
      case 'basketball':
      case 'baloncesto':
        return (
          const Color(0xFFFEE2E2),
          const Color(0xFFDC2626),
          Icons.sports_basketball,
        );
      case 'padel':
      case 'pádel':
        return (
          const Color(0xFFE0E7FF),
          const Color(0xFF4F46E5),
          Icons.sports_tennis,
        );
      default:
        return (const Color(0xFFF3F4F6), const Color(0xFF6B7280), Icons.sports);
    }
  }

  String _capitalize(String s) =>
      s.isEmpty ? s : '${s[0].toUpperCase()}${s.substring(1)}';

  String _formatDuration(Duration d) {
    final h = d.inMinutes ~/ 60;
    final m = d.inMinutes % 60;
    if (h == 0) return '$m min';
    if (m == 0) return '$h ${h == 1 ? 'hora' : 'horas'}';
    return '$h h $m min';
  }

  // ---------- UI ----------

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Detalle de cancha')),
      body: FutureBuilder<Court>(
        future: _futureCourt,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }
          if (snapshot.hasError || !snapshot.hasData) {
            return const Center(child: Text('No se pudo cargar la cancha.'));
          }

          final court = snapshot.data!;
          final (bg, accent, icon) = _sportStyle(court.type);

          return SingleChildScrollView(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _CourtImage(court: court, bg: bg, accent: accent, icon: icon),
                const SizedBox(height: 16),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Text(
                        court.name,
                        style: Theme.of(context).textTheme.headlineSmall,
                      ),
                    ),
                    Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 10,
                        vertical: 4,
                      ),
                      decoration: BoxDecoration(
                        color: bg,
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Text(
                        _capitalize(court.type),
                        style: TextStyle(
                          color: accent,
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 6),
                Row(
                  children: [
                    Icon(
                      Icons.location_on_outlined,
                      size: 16,
                      color: Colors.grey[600],
                    ),
                    const SizedBox(width: 4),
                    Text(
                      court.location ?? 'Sin ubicación',
                      style: Theme.of(context).textTheme.bodySmall,
                    ),
                  ],
                ),
                const SizedBox(height: 8),
                Text(
                  '\$${court.pricePerHour.toStringAsFixed(0)} / hora',
                  style: const TextStyle(
                    color: _brandColor,
                    fontWeight: FontWeight.bold,
                    fontSize: 18,
                  ),
                ),
                if (court.description != null) ...[
                  const SizedBox(height: 12),
                  Text(court.description!),
                ],
                const SizedBox(height: 24),
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Reservar',
                          style: Theme.of(context).textTheme.titleMedium,
                        ),
                        const SizedBox(height: 12),
                        _SelectionButton(
                          active: _selectedDate != null,
                          brandColor: _brandColor,
                          icon: Icons.calendar_today,
                          activeIcon: Icons.event_available,
                          label: _selectedDate == null
                              ? 'Elegir fecha'
                              : _capitalize(
                                  DateFormat(
                                    'EEEE d MMM yyyy',
                                    ApiConfig.locale,
                                  ).format(_selectedDate!),
                                ),
                          onPressed: _pickDate,
                        ),
                        const SizedBox(height: 8),
                        Row(
                          children: [
                            Expanded(
                              child: _SelectionButton(
                                active: _startTime != null,
                                brandColor: _brandColor,
                                icon: Icons.access_time,
                                label:
                                    _startTime?.format(context) ??
                                    'Hora inicio',
                                onPressed: () => _pickTime(true),
                              ),
                            ),
                            const SizedBox(width: 8),
                            Expanded(
                              child: _SelectionButton(
                                active: _endTime != null,
                                brandColor: _brandColor,
                                icon: Icons.access_time_filled,
                                label: _endTime?.format(context) ?? 'Hora fin',
                                onPressed: () => _pickTime(false),
                              ),
                            ),
                          ],
                        ),
                        if (_startTime != null &&
                            _endTime != null &&
                            _endTime!.after(_startTime!)) ...[
                          const SizedBox(height: 16),
                          _SummaryBar(
                            duration: _combine(
                              _selectedDate!,
                              _endTime!,
                            ).difference(_combine(_selectedDate!, _startTime!)),
                            total:
                                court.pricePerHour *
                                (_combine(_selectedDate!, _endTime!)
                                        .difference(
                                          _combine(_selectedDate!, _startTime!),
                                        )
                                        .inMinutes /
                                    60),
                            brandColor: _brandColor,
                            formatDuration: _formatDuration,
                          ),
                        ],
                        const SizedBox(height: 20),
                        SizedBox(
                          width: double.infinity,
                          child: FilledButton(
                            onPressed: _submitting
                                ? null
                                : () => _confirmReservation(court.id),
                            child: _submitting
                                ? const SizedBox(
                                    height: 20,
                                    width: 20,
                                    child: CircularProgressIndicator(
                                      strokeWidth: 2,
                                      color: Colors.white,
                                    ),
                                  )
                                : const Text('Confirmar reserva'),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}

// Comparación simple de horas del día, evita repetir la lógica hour/minute.
extension on TimeOfDay {
  bool after(TimeOfDay other) =>
      hour > other.hour || (hour == other.hour && minute > other.minute);
}

/// Imagen o placeholder de la cancha, según tenga foto o no.
class _CourtImage extends StatelessWidget {
  final Court court;
  final Color bg;
  final Color accent;
  final IconData icon;

  const _CourtImage({
    required this.court,
    required this.bg,
    required this.accent,
    required this.icon,
  });

  @override
  Widget build(BuildContext context) {
    final placeholder = Container(
      height: 180,
      width: double.infinity,
      color: bg,
      child: Center(child: Icon(icon, size: 64, color: accent)),
    );
    return ClipRRect(
      borderRadius: BorderRadius.circular(12),
      child: court.imageUrl == null
          ? placeholder
          : Image.network(
              court.imageUrl!,
              height: 180,
              width: double.infinity,
              fit: BoxFit.cover,
              errorBuilder: (_, __, ___) => placeholder,
            ),
    );
  }
}

/// Botón reutilizable para "Elegir fecha" / "Hora inicio" / "Hora fin".
/// Se pinta en verde de marca cuando ya tiene un valor seleccionado.
class _SelectionButton extends StatelessWidget {
  final bool active;
  final Color brandColor;
  final IconData icon;
  final IconData? activeIcon;
  final String label;
  final VoidCallback onPressed;

  const _SelectionButton({
    required this.active,
    required this.brandColor,
    required this.icon,
    this.activeIcon,
    required this.label,
    required this.onPressed,
  });

  @override
  Widget build(BuildContext context) {
    return OutlinedButton.icon(
      onPressed: onPressed,
      style: OutlinedButton.styleFrom(
        foregroundColor: active ? brandColor : null,
        side: BorderSide(
          color: active ? brandColor : Theme.of(context).colorScheme.outline,
          width: active ? 1.5 : 1,
        ),
        padding: const EdgeInsets.symmetric(vertical: 14),
      ),
      icon: Icon(active ? (activeIcon ?? icon) : icon),
      label: Text(label, style: const TextStyle(fontWeight: FontWeight.w500)),
    );
  }
}

/// Franja verde con duración y precio total, visible cuando ya hay
/// fecha + hora inicio + hora fin seleccionados.
class _SummaryBar extends StatelessWidget {
  final Duration duration;
  final double total;
  final Color brandColor;
  final String Function(Duration) formatDuration;

  const _SummaryBar({
    required this.duration,
    required this.total,
    required this.brandColor,
    required this.formatDuration,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
      decoration: BoxDecoration(
        color: brandColor.withValues(alpha: 0.08),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Row(
            children: [
              Icon(Icons.timelapse, size: 18, color: brandColor),
              const SizedBox(width: 6),
              Text(
                formatDuration(duration),
                style: TextStyle(
                  color: brandColor,
                  fontWeight: FontWeight.w600,
                  fontSize: 13,
                ),
              ),
            ],
          ),
          Text(
            'Total \$${total.toStringAsFixed(0)}',
            style: TextStyle(
              color: brandColor,
              fontWeight: FontWeight.w700,
              fontSize: 15,
            ),
          ),
        ],
      ),
    );
  }
}

/// Hoja inferior con los horarios disponibles (8:00 AM–10:00 PM),
/// agrupados por franja del día (Mañana / Tarde / Noche).
class _TimeSlotSheet extends StatelessWidget {
  final bool isStart;
  final DateTime selectedDate;
  final TimeOfDay? startTime;
  final TimeOfDay? endTime;
  final Color brandColor;
  final List<(String, IconData, int, int)> periods;
  final List<TimeOfDay> Function(int, int) slotsInRange;
  final bool Function(TimeOfDay) isPast;
  final ValueChanged<TimeOfDay> onSelected;

  const _TimeSlotSheet({
    required this.isStart,
    required this.selectedDate,
    required this.startTime,
    required this.endTime,
    required this.brandColor,
    required this.periods,
    required this.slotsInRange,
    required this.isPast,
    required this.onSelected,
  });

  bool _disabled(TimeOfDay slot) {
    if (isPast(slot)) return true;
    if (!isStart && startTime != null && !slot.after(startTime!)) return true;
    return false;
  }

  @override
  Widget build(BuildContext context) {
    return SafeArea(
      child: Padding(
        padding: const EdgeInsets.fromLTRB(20, 12, 20, 20),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Center(
              child: Container(
                width: 40,
                height: 4,
                margin: const EdgeInsets.only(bottom: 16),
                decoration: BoxDecoration(
                  color: Colors.grey[300],
                  borderRadius: BorderRadius.circular(4),
                ),
              ),
            ),
            Row(
              children: [
                Icon(
                  isStart
                      ? Icons.play_circle_outline
                      : Icons.stop_circle_outlined,
                  color: brandColor,
                  size: 22,
                ),
                const SizedBox(width: 8),
                Text(
                  isStart ? 'Hora de inicio' : 'Hora de fin',
                  style: Theme.of(context).textTheme.titleMedium?.copyWith(
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ],
            ),
            Padding(
              padding: const EdgeInsets.only(left: 30, top: 4),
              child: Text(
                DateFormat('EEEE d MMM', ApiConfig.locale).format(selectedDate),
                style: TextStyle(color: Colors.grey[600], fontSize: 13),
              ),
            ),
            const SizedBox(height: 18),
            ConstrainedBox(
              constraints: BoxConstraints(
                maxHeight: MediaQuery.of(context).size.height * 0.55,
              ),
              child: SingleChildScrollView(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    for (final (label, icon, from, to) in periods)
                      _periodSection(
                        context,
                        label,
                        icon,
                        slotsInRange(from, to),
                      ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _periodSection(
    BuildContext context,
    String label,
    IconData icon,
    List<TimeOfDay> slots,
  ) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 18),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Icon(icon, size: 16, color: Colors.grey[500]),
              const SizedBox(width: 6),
              Text(
                label,
                style: TextStyle(
                  color: Colors.grey[600],
                  fontSize: 12,
                  fontWeight: FontWeight.w600,
                  letterSpacing: 0.3,
                ),
              ),
            ],
          ),
          const SizedBox(height: 10),
          Wrap(
            spacing: 10,
            runSpacing: 10,
            children: [for (final slot in slots) _chip(context, slot)],
          ),
        ],
      ),
    );
  }

  Widget _chip(BuildContext context, TimeOfDay slot) {
    final disabled = _disabled(slot);
    final current = isStart ? startTime : endTime;
    final selected =
        current != null &&
        current.hour == slot.hour &&
        current.minute == slot.minute;

    return ChoiceChip(
      avatar: selected
          ? const Icon(Icons.check, size: 16, color: Colors.white)
          : null,
      label: Text(slot.format(context)),
      selected: selected,
      onSelected: disabled
          ? null
          : (_) {
              onSelected(slot);
              Navigator.pop(context);
            },
      labelStyle: TextStyle(
        color: disabled ? Colors.grey[400] : (selected ? Colors.white : null),
        fontWeight: selected ? FontWeight.w600 : FontWeight.w400,
      ),
      selectedColor: brandColor,
      backgroundColor: disabled
          ? Colors.grey[100]
          : Theme.of(context).colorScheme.surfaceContainerHighest,
      side: BorderSide(color: selected ? brandColor : Colors.transparent),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
    );
  }
}
