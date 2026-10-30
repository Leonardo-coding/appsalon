# AppSalon – Sistema de Gestión de Citas para Salón de Belleza

**Proyecto final – Programación orientada a la web**
**Entrega intermedia – Semana 11**

Autor: [Tu nombre completo]
Repositorio: [URL del repositorio en GitHub]

AppSalon es una aplicación web para la gestión de un salón de belleza. En esta entrega intermedia se implementan la base de datos, la autenticación de usuarios con roles (cliente y administrador) y la gestión del catálogo de servicios.

---

## Tecnologías utilizadas

| Componente | Tecnología |
|---|---|
| Lenguaje | PHP 8.2 o superior (desarrollado con PHP 8.3) |
| Framework | Laravel 12 (arquitectura MVC) |
| Base de datos | MySQL / MariaDB (incluido en XAMPP) |
| Plantillas | Blade |
| Interfaz | Bootstrap 5.3 (cargado por CDN) |
| Dependencias | Composer |
| Control de versiones | Git / GitHub |
| Entorno local | XAMPP (Windows) |

---

## Requisitos previos

- PHP 8.2 o superior con las extensiones habituales de Laravel (`zip`, `mbstring`, `openssl`, `pdo_mysql`, `fileinfo`). XAMPP las trae casi todas; `zip` hay que activarla quitando el `;` de la línea `extension=zip` en `php.ini`.
- Composer 2.x
- MySQL o MariaDB en ejecución (panel de XAMPP)
- Git
- Conexión a internet (para instalar dependencias y cargar Bootstrap desde CDN)

---

## Instalación

1. **Clonar el repositorio** dentro de la carpeta de XAMPP:

   ```
   cd C:\xampp\htdocs
   git clone [URL del repositorio] appsalon
   cd appsalon
   ```

2. **Instalar las dependencias de PHP:**

   ```
   composer install
   ```

3. **Crear el archivo de configuración** a partir del ejemplo y generar la clave de la aplicación:

   ```
   copy .env.example .env
   php artisan key:generate
   ```

4. **Iniciar MySQL** desde el panel de control de XAMPP y crear una base de datos vacía llamada `appsalon` (cotejamiento `utf8mb4_unicode_ci`) desde phpMyAdmin (`http://localhost/phpmyadmin`).

5. **Revisar la conexión a la base de datos** en el archivo `.env`:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=appsalon
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Crear las tablas y cargar los datos de prueba.** Hay dos opciones:

   - **Opción A (recomendada):** con migraciones y seeder de Laravel:

     ```
     php artisan migrate --seed
     ```

   - **Opción B:** importar el script SQL incluido en `database/appsalon.sql` desde la pestaña **Importar** de phpMyAdmin, seleccionando antes la base `appsalon`.

7. **Iniciar el servidor de desarrollo:**

   ```
   php artisan serve
   ```

   Abrir en el navegador: <http://127.0.0.1:8000>

---

## Credenciales de usuarios de prueba

| Rol | Correo | Contraseña |
|---|---|---|
| Administrador | `admin@appsalon.com` | `Admin123` |
| Cliente | `cliente@appsalon.com` | `Cliente123` |

---

## Funcionalidades implementadas

### Autenticación y seguridad
- Registro de usuarios con validación de campos en el servidor (nombre, apellido, correo único, teléfono opcional y contraseña de mínimo 6 caracteres con confirmación).
- Inicio y cierre de sesión.
- Contraseñas almacenadas con hash bcrypt.
- Sesiones con regeneración de identificador al iniciar sesión y al cerrarla, para evitar fijación de sesión.
- Límite de 5 intentos de inicio de sesión por minuto.
- Mensaje de error genérico en el login, que no revela si el correo existe.
- Roles de usuario (cliente y administrador) controlados por el middleware `AdminMiddleware`.

### Gestión de servicios
- Catálogo público de servicios con su precio, visible sin iniciar sesión.
- CRUD de servicios (crear, editar y eliminar) disponible solo para administradores.
- Validación de datos: nombre obligatorio y único, precio numérico mayor que cero con máximo 2 decimales.
- Integridad referencial: no se puede eliminar un servicio que ya tiene citas asociadas; se muestra un mensaje de error.

### Interfaz
- Diseño responsive con Bootstrap (menú colapsable y tarjetas que se adaptan al ancho).
- Mensajes de éxito y de error, y validación visual en los formularios.
- Páginas de error personalizadas para 403 (acceso denegado) y 404 (no encontrada).

### Medidas de seguridad aplicadas
| Riesgo | Medida |
|---|---|
| CSRF | Token `@csrf` en todos los formularios |
| XSS | Escape automático de Blade (`{{ }}`) |
| Inyección SQL | Eloquent / consultas preparadas |
| Contraseñas | Hash bcrypt (cast `hashed` del modelo) |
| Asignación masiva | Propiedad `$fillable` en los modelos; el campo `admin` nunca se toma del formulario |
| Acceso no autorizado | Middleware `auth` y `admin` en las rutas, y verificación adicional en `ServiceRequest` |
| Fuerza bruta | Middleware `throttle` en el login |

---

## Base de datos

| Tabla | Campos |
|---|---|
| `usuarios` | id, nombre, apellido, email (único), password, telefono, admin, confirmado, token |
| `servicios` | id, nombre, precio |
| `citas` | id, fecha, hora, usuarioId |
| `citasServicios` | id, citaId, servicioId |

**Relaciones (claves foráneas):**
- `citas.usuarioId` → `usuarios.id` (borrado en cascada)
- `citasServicios.citaId` → `citas.id` (borrado en cascada)
- `citasServicios.servicioId` → `servicios.id` (borrado restringido: no se elimina un servicio con citas)

**Datos de prueba:** 2 usuarios (administrador y cliente), 6 servicios y 1 cita de ejemplo con 2 servicios.

---

## Rutas principales

| Método | Ruta | Descripción | Acceso |
|---|---|---|---|
| GET | `/` | Página de inicio | Público |
| GET | `/servicios` | Catálogo de servicios | Público |
| GET / POST | `/registro` | Registro de usuarios | Visitantes |
| GET / POST | `/login` | Inicio de sesión | Visitantes |
| POST | `/logout` | Cierre de sesión | Autenticados |
| GET | `/admin/servicios/crear` | Formulario de nuevo servicio | Administrador |
| POST | `/admin/servicios` | Guardar servicio | Administrador |
| GET | `/admin/servicios/{id}/editar` | Formulario de edición | Administrador |
| PUT | `/admin/servicios/{id}` | Actualizar servicio | Administrador |
| DELETE | `/admin/servicios/{id}` | Eliminar servicio | Administrador |

---

## Estructura del proyecto (MVC)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php        # Registro, login y logout
│   │   └── ServiceController.php     # Catálogo y CRUD de servicios
│   ├── Middleware/
│   │   └── AdminMiddleware.php       # Restringe rutas a administradores
│   └── Requests/
│       ├── RegisterRequest.php       # Validación del registro
│       ├── LoginRequest.php          # Validación del login
│       └── ServiceRequest.php        # Validación de servicios
└── Models/
    ├── User.php                      # Tabla usuarios
    ├── Service.php                   # Tabla servicios
    ├── Appointment.php               # Tabla citas
    └── AppointmentService.php        # Tabla citasServicios
database/
├── migrations/                       # Estructura de las 4 tablas
├── seeders/DatabaseSeeder.php        # Datos de prueba
└── appsalon.sql                      # Script SQL completo
resources/views/
├── layouts/app.blade.php             # Plantilla base
├── auth/                             # Login y registro
├── servicios/                        # Catálogo y formulario
├── errors/                           # Páginas 403 y 404
└── home.blade.php
routes/web.php                        # Definición de rutas
```

---

## Alcance de esta entrega

Esta entrega corresponde a la **semana 11**. Quedan para la entrega final (semana 16): reserva de citas con calendario, validación de disponibilidad, recuperación de contraseña por correo, confirmación de cuenta por token (actualmente las cuentas nuevas se marcan como confirmadas automáticamente), panel de administración con estadísticas, API REST, reportes y exportación a PDF y Excel.
