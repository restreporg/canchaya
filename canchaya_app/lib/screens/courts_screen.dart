import 'package:flutter/material.dart';
import '../models/court.dart';
import '../services/api_exception.dart';
import '../services/court_service.dart';
import 'court_detail_screen.dart';

class CourtsScreen extends StatefulWidget {
  const CourtsScreen({super.key});

  @override
  State<CourtsScreen> createState() => _CourtsScreenState();
}

class _CourtsScreenState extends State<CourtsScreen> {
  final _courtService = CourtService();
  late Future<List<Court>> _futureCourts;

  @override
  void initState() {
    super.initState();
    _futureCourts = _courtService.getCourts();
  }

  void _reload() {
    setState(() {
      _futureCourts = _courtService.getCourts();
    });
  }

  @override
  Widget build(BuildContext context) {
    return RefreshIndicator(
      onRefresh: () async => _reload(),
      child: FutureBuilder<List<Court>>(
        future: _futureCourts,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          }

          if (snapshot.hasError) {
            final message = snapshot.error is ApiException
                ? (snapshot.error as ApiException).message
                : 'Ocurrió un error al cargar las canchas.';
            return ListView(
              children: [
                const SizedBox(height: 80),
                Icon(Icons.error_outline, size: 48, color: Colors.grey[400]),
                const SizedBox(height: 12),
                Center(child: Text(message, textAlign: TextAlign.center)),
                const SizedBox(height: 12),
                Center(
                  child: OutlinedButton(onPressed: _reload, child: const Text('Reintentar')),
                ),
              ],
            );
          }

          final courts = snapshot.data ?? [];
          if (courts.isEmpty) {
            return const Center(child: Text('No hay canchas disponibles todavía.'));
          }

          return ListView.builder(
            padding: const EdgeInsets.all(12),
            itemCount: courts.length,
            itemBuilder: (context, index) {
              final court = courts[index];
              return Card(
                clipBehavior: Clip.antiAlias,
                margin: const EdgeInsets.only(bottom: 12),
                child: ListTile(
                  contentPadding: const EdgeInsets.all(12),
                  leading: court.imageUrl != null
                      ? ClipRRect(
                          borderRadius: BorderRadius.circular(8),
                          child: Image.network(
                            court.imageUrl!,
                            width: 64,
                            height: 64,
                            fit: BoxFit.cover,
                            errorBuilder: (_, __, ___) => const Icon(Icons.sports_soccer, size: 40),
                          ),
                        )
                      : const Icon(Icons.sports_soccer, size: 40),
                  title: Text(court.name, style: const TextStyle(fontWeight: FontWeight.bold)),
                  subtitle: Text('${court.type} · ${court.location ?? 'Sin ubicación'}'),
                  trailing: Text('\$${court.pricePerHour.toStringAsFixed(0)}/h'),
                  onTap: () {
                    Navigator.of(context).push(
                      MaterialPageRoute(builder: (_) => CourtDetailScreen(courtId: court.id)),
                    );
                  },
                ),
              );
            },
          );
        },
      ),
    );
  }
}
