# Proyecto: CanchaYa
 Estudiantes:
 -Santiago Restrepo Santamaria.
 -Juan Diego Perez.
 -David Esnaider Quiñonez.

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
- Laravel Herd

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

---

# Uso de Insomnia para probar la API

Este proyecto incluye una colección de Insomnia (`Reservas Canchas API`) con todos los endpoints organizados por módulo (Autenticación, Explorar canchas, Cliente, Admin, etc). A continuación se explica cómo importarla, configurarla y, en especial, cómo manejar el **token de autenticación** para probar rutas protegidas.

## 1. Importar la colección

1. Abre Insomnia.
2. Ve a `Application > Preferences` o usa el botón `+` junto a las pestañas para crear un nuevo proyecto/importar.
3. Selecciona `Import` y elige el archivo de la colección exportada (`.json` o `.yaml`) que se encuentra en la carpeta del repositorio (o clónala directamente si el proyecto está vinculado por Git Sync).
4. Verifica que aparezcan las carpetas: `01 - Público`, `02 - Cliente` y `03 - Admin` (según corresponda) con sus respectivos requests.

## 2. Configurar el entorno base (Base Environment)

1. Haz clic en `Base Environment` en la parte superior del workspace.
2. Define al menos las siguientes variables:
   ```json
   {
     "base_url": "https://canchaya.test/api",
     "token": ""
   }
   ```
3. Deja `token` vacío por ahora; se llenará automáticamente en el paso siguiente.
4. En cada request, la URL debe usar la variable, por ejemplo: `{{ _.base_url }}/reservations`.
5. (PARA EJECUTAR LA API REST SE DEBE HACER LAS PRUEBAS CON EL SERVIDOR DE LARAVEL HERD)

## 3. Obtener el token (Login)

1. Levanta el servidor local con `php artisan serve`.
2. En la carpeta `1. Autenticación`, abre el request **`2. Login`**.
3. En el `Body` (normalmente `JSON`), ingresa las credenciales de prueba, por ejemplo:
   ```json
   {
     "email": "carlos@gmail.com",
     "password": "password"
   }
   ```
4. Presiona `Send`. Si Laravel usa Sanctum/Passport, la respuesta traerá algo como:
   ```json
   {
     "user": { "id": 1, "name": "Carlos", "role": "cliente" },
     "token": "1|AbCdEf123456..."
   }
   ```
5. **Copia el valor de `token`** (sin las comillas).

## 4. Guardar el token en el entorno (para no pegarlo en cada request)

1. Vuelve a `Base Environment` (o crea un `Environment` específico como "Cliente" o "Admin" si vas a probar ambos roles).
2. Pega el valor copiado en la variable `token`:
   ```json
   {
     "base_url": "https://canchaya.test/api",
     "token": "1|AbCdEf123456..."
   }
   ```
3. Guarda los cambios.

> 💡 **Tip:** también puedes automatizar esto usando un script en la pestaña `Tests`/`After Response` del request de Login para que Insomnia guarde el token automáticamente en la variable de entorno cada vez que hagas login, sin copiar y pegar manualmente.

## 5. Usar el token en las rutas protegidas

1. Abre cualquier request que requiera autenticación (por ejemplo, `3. Me` o cualquier endpoint dentro de `02 - Cliente` o `03 - Admin`).
2. Ve a la pestaña `Auth` del request.
3. Selecciona el tipo `Bearer Token`.
4. En el campo `Token`, escribe: `{{ _.token }}` (esto hace referencia a la variable que guardaste en el entorno).
5. Presiona `Send`. Si el token es válido, la petición debe responder con status `200 OK` y los datos del usuario o recurso solicitado.

Si prefieres no configurar `Auth` en cada request individualmente, puedes definir el `Bearer Token` a nivel de carpeta (clic derecho sobre la carpeta `02 - Cliente` → `Edit` → pestaña `Auth`), y todos los requests dentro heredarán esa configuración automáticamente.

## 6. Verificar expiración o token inválido

- Si obtienes un `401 Unauthorized`, el token pudo haber expirado o la sesión fue cerrada (`4. Logout`). En ese caso, repite el paso 3 (Login) para obtener un token nuevo.
- Recuerda que si usas `Logout`, el token actual queda invalidado en el backend y ya no servirá para las siguientes peticiones.

## 7. Probar con distintos roles

Como el proyecto maneja roles (`admin` y `cliente`), se recomienda:
- Crear **dos entornos distintos** en Insomnia (`Entorno Cliente` y `Entorno Admin`), cada uno con su propio `token`.
- Iniciar sesión con las credenciales correspondientes de la tabla de credenciales de prueba y guardar el token en el entorno correspondiente.
- Cambiar de entorno desde el selector superior (`Base Environment`) según qué rol quieras probar.

---

# IMAGENES DEL PROYECTO

# Pagina de inicio

<img width="1919" height="971" alt="Captura de pantalla 2026-07-11 202655" src="https://github.com/user-attachments/assets/91c6e812-8a98-4061-b358-83c3f950481e" />


# Loggins (register, iniciar sesion)

<img width="1919" height="1020" alt="Captura de pantalla 2026-07-11 202715" src="https://github.com/user-attachments/assets/b406a045-6783-4bc4-b6d1-93650e598b50" />
<img width="1899" height="1017" alt="Captura de pantalla 2026-07-11 202727" src="https://github.com/user-attachments/assets/442d3367-9892-4660-801f-07b839cee243" />


# Apartado de Admin

<img width="1919" height="910" alt="Captura de pantalla 2026-07-11 204107" src="https://github.com/user-attachments/assets/056bc6f8-aac7-4126-bcbb-d14cc1602549" />
<img width="1919" height="915" alt="Captura de pantalla 2026-07-11 204058" src="https://github.com/user-attachments/assets/c7429895-b546-41b0-9f69-c23e8ca3b450" />
<img width="1919" height="1023" alt="Captura de pantalla 2026-07-11 204043" src="https://github.com/user-attachments/assets/d99ad35c-4b8a-4d5e-bb97-697729de8915" />
<img width="1917" height="900" alt="Captura de pantalla 2026-07-11 204116" src="https://github.com/user-attachments/assets/8a7f9104-ae28-4156-b494-deb089bb67e2" />


# Apartado de cliente 

<img width="1919" height="970" alt="Captura de pantalla 2026-07-11 204218" src="https://github.com/user-attachments/assets/a463faa5-1863-445d-a3bb-c9a0f5e820fa" />
<img width="1917" height="961" alt="Captura de pantalla 2026-07-11 204158" src="https://github.com/user-attachments/assets/8e896fbb-090b-43ec-ad33-a8bd3324b7ad" />
<img width="1919" height="909" alt="Captura de pantalla 2026-07-11 204148" src="https://github.com/user-attachments/assets/8743ef3a-741a-491d-9f29-613a931e6b27" />
<img width="1919" height="1014" alt="Captura de pantalla 2026-07-11 204234" src="https://github.com/user-attachments/assets/bdee4f1c-27b3-434f-b21c-c57e2cf56808" />
