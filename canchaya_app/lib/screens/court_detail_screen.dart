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

  /// Arma el DateTime combinando la fecha elegida con la hora dada.
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

    // La hora de fin debe ser después de la hora de inicio.
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

    // No se puede reservar un horario que ya pasó.
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
          return SingleChildScrollView(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                if (court.imageUrl != null)
                  ClipRRect(
                    borderRadius: BorderRadius.circular(12),
                    child: Image.network(
                      court.imageUrl!,
                      height: 180,
                      width: double.infinity,
                      fit: BoxFit.cover,
                    ),
                  ),
                const SizedBox(height: 16),
                Text(
                  court.name,
                  style: Theme.of(context).textTheme.headlineSmall,
                ),
                const SizedBox(height: 4),
                Text('${court.type} · ${court.location ?? 'Sin ubicación'}'),
                const SizedBox(height: 8),
                Text(
                  '\$${court.pricePerHour.toStringAsFixed(0)} / hora',
                  style: const TextStyle(
                    fontWeight: FontWeight.bold,
                    fontSize: 16,
                  ),
                ),
                if (court.description != null) ...[
                  const SizedBox(height: 12),
                  Text(court.description!),
                ],
                const Divider(height: 32),
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
                        label: Text(_endTime?.format(context) ?? 'Hora fin'),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 20),
                FilledButton(
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
              ],
            ),
          );
        },
      ),
    );
  }
}
