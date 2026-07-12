# Proyecto: CanchaYa
 Estudiantes:
 Santiago Restrepo Santamaria
 Juan Diego Perez 
 David Esnaider Quiñonez

# Descripción
Canchaya es una plataforma web de reservas de canchas deportivas desarrollada en Laravel.
Permite a los clientes explorar canchas disponibles filtradas por deporte y ubicación, realizar reservas, efectuar pagos y dejar reseñas.
Los administradores cuentan con un panel completo para gestionar canchas, horarios, reservas, pagos y reseñas.


# Tablas implementadas y sus relaciones

# Tablas
| Tabla | Descripción |
|-------|-------------|
| `users` | Usuarios del sistema (admin y clientes) |
| `courts` | Canchas deportivas disponibles |
| `schedules` | Horarios de disponibilidad por cancha |
| `reservations` | Reservas realizadas por los clientes |
| `payments` | Pagos asociados a cada reserva |
| `reviews` | Reseñas dejadas por los clientes |

# Relaciones
| Modelo | Relación | Modelo relacionado |
|--------|----------|--------------------|
| User | hasMany | Reservations |
| User | hasMany | Payments |
| User | hasMany | Reviews |
| Court | hasMany | Schedules |
| Court | hasMany | Reservations |
| Reservation | belongsTo | User |
| Reservation | belongsTo | Court |
| Reservation | hasOne | Payment |
| Reservation | hasOne | Review |
| Payment | belongsTo | Reservation |
| Payment | belongsTo | User |
| Review | belongsTo | Reservation |
| Review | belongsTo | User |
| Schedule | belongsTo | Court |

---


# Instrucciones para correr localmente

# Requisitos
- PHP 8.2+
- Composer
- Node.js
- WAMP / Laravel Herd / cualquier servidor local

# Pasos

(BASH)

1. Clonar el repositorio 
```
git clone 
cd canchaya
```

2. Instalar dependencias
```
composer install
npm install
```

3. Configurar el archivo de entorno
```
cp .env.example .env
php artisan key:generate
```

4. Ejecutar migraciones y seeders
```
php artisan migrate:fresh --seed
```

5. Levantar el servidor
```
php artisan serve
```

6. Entrar en el navegador al localhost

# Credenciales de prueba
| Rol | Email | Contraseña |
|-----|-------|------------|
| Admin | admin@canchaya.com | password |
| Cliente | carlos@gmail.com | password |


---

# Lógica de negocio

# Roles
-Admin — gestiona toda la plataforma: canchas, horarios, reservas, pagos y reseñas.

-Cliente — puede explorar canchas, hacer reservas, pagar y dejar reseñas.

# Flujo de una reserva
1. El cliente selecciona una cancha filtrando por deporte o ubicación.
2. Elige fecha y hora de inicio y fin.
3. El sistema verifica que no haya conflictos con otras reservas en ese horario.
4. Se crea la reserva con estado "pendiente".
5. El cliente realiza el pago (efectivo, tarjeta o transferencia).
6. La reserva pasa a estado "confirmada".
7. Cuando se completa, el admin cambia el estado a "completada".
8. El cliente puede dejar una reseña con calificación de 1 a 5 estrellas.

# Estados de una reserva
| Estado | Descripción |
|--------|-------------|
| `pendiente` | Reserva creada, sin pago |
| `confirmada` | Pago realizado |
| `completada` | Reserva finalizada |
| `cancelada` | Cancelada por el cliente o el admin |

# Reglas importantes
- Un cliente solo puede ver y cancelar sus propias reservas.
- Solo se pueden reseñar reservas con estado "completada".
- No se permiten reservas en horarios ya ocupados.
- El admin puede cambiar el estado de cualquier reserva manualmente.

# Protección de rutas
- "/admin/" — requiere estar autenticado y tener "role = admin".
- "/client/" — requiere estar autenticado.
- Un cliente no puede acceder a reservas de otros clientes (Policy)
