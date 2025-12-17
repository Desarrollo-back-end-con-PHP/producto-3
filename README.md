# 🌴 Isla Transfers API & App

Sistema de gestión de reservas para traslados. Este documento detalla las credenciales de acceso, los endpoints de la API y las rutas de navegación.

## 🔐 Credenciales de Prueba

Para facilitar el testing, se han habilitado los siguientes perfiles:

### 👤 Perfil de Usuario (Cliente)
* **Email:** `user@gmail.com`
* **Contraseña:** `P@ssw0rd`

### 🏨 Perfil Corporativo (Hotel)
* **Usuario:** `hotel_iberostar`
* **Contraseña:** `password123`
* **Detalles:** Hotel Iberostar Alcudia (Comisión: 10% | Zona Norte)

### 🛡️ Perfil de Administrador
* **Email:** `admin@islatransfers.com`
* **Contraseña:** `P@ssw0rd`

---

## 🛣️ Rutas de Navegación (Frontend)

| Ruta | Acceso | Descripción |
| :--- | :--- | :--- |
| `/login` | Público | Formulario de acceso al sistema. |
| `/perfil` | Privado | Información personal del usuario o empresa. |
| `/reservas` | Privado | Listado de reservas realizadas. |
| `/reservas/nueva` | Privado | Formulario para crear una nueva reserva. |
| `/admin/dashboard` | Solo Admin | Panel de control y estadísticas. |

---

## 🚀 API Endpoints

Todos los endpoints detallados a continuación requieren autenticación mediante Token (`Bearer`).

| Método | Endpoint | Descripción | Auth |
| :--- | :--- | :--- | :---: |
| `GET` | `/api/usuario/perfil` | Consultar los datos del usuario logueado. | ✅ |
| `GET` | `/api/reservas` | Obtener el historial completo de reservas del usuario. | ✅ |
| `GET` | `/api/reservas/{id}` | Ver los detalles de una reserva específica (por ID). | ✅ |
| `GET` | `/api/reservas/form-data` | Obtener listados auxiliares (hoteles, vehículos) para formularios. | ✅ |

---

## 🛠️ Notas Técnicas
* **Autenticación:** Incluir el header `Authorization: Bearer <tu_token>`.
* **Formato:** Todas las respuestas se entregan en formato `application/json`.


# Comandos
### Limpiar cachés (Si algo no se actualiza),
./vendor/bin/sail artisan optimize:clear

### Instalar dependencias PHP (Composer)
./vendor/bin/sail composer install

### Compilar Frontend (Vite/NPM),
./vendor/bin/sail npm run dev

### Crear un Controlador,
./vendor/bin/sail artisan make:controller NombreController

### Crear un Modelo + Migración
./vendor/bin/sail artisan make:model Nombre -m

### Reiniciar la información de las BD.
./vendor/bin/sail artisan migrate:fresh --seed