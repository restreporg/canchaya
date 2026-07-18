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

  Future<void> _reload() async {
    final future = _courtService.getCourts();
    setState(() {
      _futureCourts = future;
    });
    await future.catchError((_) => <Court>[]);
  }

  // Colores pastel de fondo + ícono + color de acento, según el deporte.
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
    return RefreshIndicator(
      onRefresh: _reload,
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
                  child: OutlinedButton(
                    onPressed: _reload,
                    child: const Text('Reintentar'),
                  ),
                ),
              ],
            );
          }

          final courts = snapshot.data ?? [];
          if (courts.isEmpty) {
            return const Center(
              child: Text('No hay canchas disponibles todavía.'),
            );
          }

          return GridView.builder(
            padding: const EdgeInsets.all(12),
            gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
              crossAxisCount: 1, // 1 columna en móvil; sube a 2/3 en pantallas anchas si quieres
              mainAxisExtent: 300,
              mainAxisSpacing: 12,
            ),
            itemCount: courts.length,
            itemBuilder: (context, index) {
              final court = courts[index];
              final (bg, accent, icon) = _sportStyle(court.type);

              return Card(
                clipBehavior: Clip.antiAlias,
                child: InkWell(
                  onTap: () {
                    Navigator.of(context).push(
                      MaterialPageRoute(
                        builder: (_) => CourtDetailScreen(courtId: court.id),
                      ),
                    );
                  },
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.stretch,
                    children: [
                      // Bloque superior de color con el ícono del deporte
                      Container(
                        height: 110,
                        color: bg,
                        child: court.imageUrl != null
                            ? Image.network(
                                court.imageUrl!,
                                fit: BoxFit.cover,
                                errorBuilder: (_, __, ___) =>
                                    Center(child: Icon(icon, size: 48, color: accent)),
                              )
                            : Center(child: Icon(icon, size: 48, color: accent)),
                      ),
                      Padding(
                        padding: const EdgeInsets.all(12),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Expanded(
                                  child: Text(
                                    court.name,
                                    style: Theme.of(context).textTheme.titleMedium,
                                    overflow: TextOverflow.ellipsis,
                                  ),
                                ),
                                Container(
                                  padding: const EdgeInsets.symmetric(
                                    horizontal: 8,
                                    vertical: 3,
                                  ),
                                  decoration: BoxDecoration(
                                    color: bg,
                                    borderRadius: BorderRadius.circular(12),
                                  ),
                                  child: Text(
                                    _capitalize(court.type),
                                    style: TextStyle(
                                      color: accent,
                                      fontSize: 11,
                                      fontWeight: FontWeight.w600,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 4),
                            Row(
                              children: [
                                Icon(Icons.location_on_outlined,
                                    size: 14, color: Colors.grey[600]),
                                const SizedBox(width: 4),
                                Expanded(
                                  child: Text(
                                    court.location ?? 'Sin ubicación',
                                    style: Theme.of(context).textTheme.bodySmall,
                                    overflow: TextOverflow.ellipsis,
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 8),
                            Text(
                              '\$${court.pricePerHour.toStringAsFixed(0)}/hora',
                              style: const TextStyle(
                                color: Color(0xFF059669),
                                fontWeight: FontWeight.bold,
                                fontSize: 15,
                              ),
                            ),
                            const SizedBox(height: 8),
                            SizedBox(
                              width: double.infinity,
                              child: FilledButton.icon(
                                onPressed: () {
                                  Navigator.of(context).push(
                                    MaterialPageRoute(
                                      builder: (_) =>
                                          CourtDetailScreen(courtId: court.id),
                                    ),
                                  );
                                },
                                icon: const Icon(Icons.event_available, size: 18),
                                label: const Text('Reservar'),
                              ),
                            ),
                          ],
                        ),
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