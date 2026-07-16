import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../config/api_config.dart';
import '../main.dart';
import '../models/reservation.dart';
import '../services/api_exception.dart';
import '../services/reservation_service.dart';

class ReservationsScreen extends StatefulWidget {
  const ReservationsScreen({super.key});

  @override
  State<ReservationsScreen> createState() => _ReservationsScreenState();
}

class _ReservationsScreenState extends State<ReservationsScreen>
    with RouteAware {
  final _reservationService = ReservationService();
  late Future<List<Reservation>> _futureReservations;

  @override
  void initState() {
    super.initState();
    _futureReservations = _reservationService.getMyReservations();
  }

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    routeObserver.subscribe(this, ModalRoute.of(context)!);
  }

  @override
  void dispose() {
    routeObserver.unsubscribe(this);
    super.dispose();
  }

  /// Se llama cuando el usuario vuelve a esta pantalla después de haber
  /// navegado a otra ruta encima (ej. crear una reserva y volver).
  @override
  void didPopNext() {
    _load();
  }

  Future<void> _load() async {
    final future = _reservationService.getMyReservations();
    setState(() {
      _futureReservations = future;
    });
    await future.catchError((_) => <Reservation>[]);
  }

  Future<void> _cancel(Reservation reservation) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text('Cancelar reserva'),
        content: const Text('¿Seguro que quieres cancelar esta reserva?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('No'),
          ),
          TextButton(
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Sí, cancelar'),
          ),
        ],
      ),
    );
    if (confirmed != true) return;

    try {
      await _reservationService.cancelReservation(reservation.id);
      _load();
    } on ApiException catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(SnackBar(content: Text(e.firstFieldError)));
      }
    }
  }

  Color _statusColor(String status) {
    switch (status) {
      case 'confirmada':
        return Colors.blue;
      case 'completada':
        return Colors.green;
      case 'cancelada':
        return Colors.red;
      default:
        return Colors.orange; // pendiente
    }
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      onRefresh: _load,
      child: FutureBuilder<List<Reservation>>(
        future: _futureReservations,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }
          if (snapshot.hasError) {
            final message = snapshot.error is ApiException
                ? (snapshot.error as ApiException).message
                : 'No se pudieron cargar tus reservas.';
            return ListView(
              children: [
                const SizedBox(height: 80),
                Icon(Icons.error_outline, size: 48, color: Colors.grey[400]),
                const SizedBox(height: 12),
                Center(child: Text(message, textAlign: TextAlign.center)),
                const SizedBox(height: 12),
                Center(
                  child: OutlinedButton(
                    onPressed: _load,
                    child: const Text('Reintentar'),
                  ),
                ),
              ],
            );
          }

          final reservations = snapshot.data ?? [];
          if (reservations.isEmpty) {
            return const Center(child: Text('Todavía no tienes reservas.'));
          }

          final formatter = DateFormat('d MMM yyyy, HH:mm', ApiConfig.locale);

          return ListView.builder(
            padding: const EdgeInsets.all(12),
            itemCount: reservations.length,
            itemBuilder: (context, index) {
              final r = reservations[index];
              return Card(
                margin: const EdgeInsets.only(bottom: 12),
                child: ListTile(
                  contentPadding: const EdgeInsets.all(12),
                  title: Text(r.court?.name ?? 'Cancha'),
                  subtitle: Text(
                    '${formatter.format(r.startDatetime)} — ${DateFormat('HH:mm').format(r.endDatetime)}\n'
                    '\$${r.totalPrice.toStringAsFixed(0)}',
                  ),
                  isThreeLine: true,
                  trailing: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    crossAxisAlignment: CrossAxisAlignment.end,
                    children: [
                      Chip(
                        label: Text(
                          r.status,
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 12,
                          ),
                        ),
                        backgroundColor: _statusColor(r.status),
                        padding: EdgeInsets.zero,
                        materialTapTargetSize: MaterialTapTargetSize.shrinkWrap,
                      ),
                      if (r.status == 'pendiente')
                        TextButton(
                          onPressed: () => _cancel(r),
                          child: const Text('Cancelar'),
                        ),
                    ],
                  ),
                ),
              );
            },
          );
        },
      ),
    );
  }
}
