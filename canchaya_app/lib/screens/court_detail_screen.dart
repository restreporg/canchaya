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

  Future<void> _pickDate() async {
    final now = DateTime.now();
    final date = await showDatePicker(
      context: context,
      initialDate: now,
      firstDate: now,
      lastDate: now.add(const Duration(days: 90)),
    );
    if (date != null) setState(() => _selectedDate = date);
  }

  Future<void> _pickTime(bool isStart) async {
    final time = await showTimePicker(
      context: context,
      initialTime: TimeOfDay.now(),
    );
    if (time != null) {
      setState(() {
        if (isStart) {
          _startTime = time;
        } else {
          _endTime = time;
        }
      });
    }
  }

  DateTime _combine(DateTime date, TimeOfDay time) {
    return DateTime(date.year, date.month, date.day, time.hour, time.minute);
  }

  Future<void> _confirmReservation(int courtId) async {
    if (_selectedDate == null || _startTime == null || _endTime == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Selecciona fecha, hora de inicio y hora de fin.'),
        ),
      );
      return;
    }

    final start = _combine(_selectedDate!, _startTime!);
    final end = _combine(_selectedDate!, _endTime!);

    if (!end.isAfter(start)) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text(
            'La hora de fin debe ser posterior a la hora de inicio.',
          ),
        ),
      );
      return;
    }

    if (start.isBefore(DateTime.now())) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('No puedes reservar un horario que ya pasó.'),
        ),
      );
      return;
    }

    final formatter = DateFormat('yyyy-MM-dd HH:mm:ss');

    setState(() => _submitting = true);
    try {
      await _reservationService.createReservation(
        courtId: courtId,
        start: formatter.format(start),
        end: formatter.format(end),
      );
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('¡Reserva creada! Puedes verla en "Mis reservas".'),
          ),
        );
        Navigator.of(context).pop();
      }
    } on ApiException catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(SnackBar(content: Text(e.firstFieldError)));
      }
    } finally {
      if (mounted) setState(() => _submitting = false);
    }
  }

  // Mismo mapeo de colores/íconos que en courts_screen.dart, para consistencia.
  (Color bg, Color accent, IconData icon) _sportStyle(String type) {
    switch (type.toLowerCase()) {
      case 'futbol':
      case 'fútbol':
        return (const Color(0xFFD1FAE5), const Color(0xFF059669), Icons.sports_soccer);
      case 'tenis':
        return (const Color(0xFFFEF3C7), const Color(0xFFD97706), Icons.sports_tennis);
      case 'basketball':
      case 'baloncesto':
        return (const Color(0xFFFEE2E2), const Color(0xFFDC2626), Icons.sports_basketball);
      case 'padel':
      case 'pádel':
        return (const Color(0xFFE0E7FF), const Color(0xFF4F46E5), Icons.sports_tennis);
      default:
        return (const Color(0xFFF3F4F6), const Color(0xFF6B7280), Icons.sports);
    }
  }

  String _capitalize(String s) =>
      s.isEmpty ? s : '${s[0].toUpperCase()}${s.substring(1)}';

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
                // Bloque superior: imagen real, o el mismo estilo pastel
                // que usamos en las tarjetas de courts_screen.dart.
                ClipRRect(
                  borderRadius: BorderRadius.circular(12),
                  child: court.imageUrl != null
                      ? Image.network(
                          court.imageUrl!,
                          height: 180,
                          width: double.infinity,
                          fit: BoxFit.cover,
                          errorBuilder: (_, __, ___) => Container(
                            height: 180,
                            width: double.infinity,
                            color: bg,
                            child: Center(
                              child: Icon(icon, size: 64, color: accent),
                            ),
                          ),
                        )
                      : Container(
                          height: 180,
                          width: double.infinity,
                          color: bg,
                          child: Center(
                            child: Icon(icon, size: 64, color: accent),
                          ),
                        ),
                ),
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
                    Icon(Icons.location_on_outlined,
                        size: 16, color: Colors.grey[600]),
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
                    color: Color(0xFF059669),
                    fontWeight: FontWeight.bold,
                    fontSize: 18,
                  ),
                ),
                if (court.description != null) ...[
                  const SizedBox(height: 12),
                  Text(court.description!),
                ],
                const SizedBox(height: 24),

                // Formulario de reserva agrupado en tarjeta.
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
                        OutlinedButton.icon(
                          onPressed: _pickDate,
                          icon: const Icon(Icons.calendar_today),
                          label: Text(
                            _selectedDate == null
                                ? 'Elegir fecha'
                                : DateFormat(
                                    'EEEE d MMM yyyy',
                                    ApiConfig.locale,
                                  ).format(_selectedDate!),
                          ),
                        ),
                        const SizedBox(height: 8),
                        Row(
                          children: [
                            Expanded(
                              child: OutlinedButton.icon(
                                onPressed: () => _pickTime(true),
                                icon: const Icon(Icons.access_time),
                                label: Text(
                                  _startTime?.format(context) ?? 'Hora inicio',
                                ),
                              ),
                            ),
                            const SizedBox(width: 8),
                            Expanded(
                              child: OutlinedButton.icon(
                                onPressed: () => _pickTime(false),
                                icon: const Icon(Icons.access_time_filled),
                                label: Text(
                                    _endTime?.format(context) ?? 'Hora fin'),
                              ),
                            ),
                          ],
                        ),
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