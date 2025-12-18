-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: mysql
-- Tiempo de generación: 18-12-2025 a las 09:40:05
-- Versión del servidor: 8.0.32
-- Versión de PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `isla_transfers`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_11_20_150609_create_transfer_zonas_table', 1),
(2, '2025_11_20_151443_create_transfer_hotels_table', 1),
(3, '2025_11_20_152251_create_transfer_tipo_reservas_table', 1),
(4, '2025_11_20_152334_create_transfer_vehiculos_table', 1),
(5, '2025_11_20_152502_create_transfer_viajeros_table', 1),
(6, '2025_11_20_153410_create_transfer_reservas_table', 1),
(7, '2025_11_20_160347_create_transfer_precios_table', 1),
(8, '2025_11_20_160448_create_reserva_admin_table', 1),
(9, '2025_11_20_172505_create_sessions_table', 1),
(10, '2025_11_21_161946_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva_admin`
--

CREATE TABLE `reserva_admin` (
  `id` bigint UNSIGNED NOT NULL,
  `id_reserva` bigint UNSIGNED NOT NULL,
  `id_admin` int NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('F7FnMOadtW39sLZhweYBWa9SuF6yhLziqc5mohWz', 5, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYUo2WjI0ejIwRG9pNW10UVF1M0V2SGxRRkVBOU9YTTRwU0J5d0JreiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3QvYWRtaW4vaG90ZWxlcyI7czo1OiJyb3V0ZSI7czoxOToiYWRtaW4uaG90ZWxlcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==', 1766046622),
('lisxQ8QO41dk9xOhlba9DMVnv4SmoxtfqPlGoHtJ', 5, '172.18.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieUtxbng1OGdvZE9adDNQanJPcFltUlM4YWcxeVl5RWo4aXBXTXU0NCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3QvYWRtaW4vcmVzZXJ2YXMiO3M6NToicm91dGUiO3M6MjA6ImFkbWluLnJlc2VydmFzLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1765987553);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transfer_hotels`
--

CREATE TABLE `transfer_hotels` (
  `id_hotel` bigint UNSIGNED NOT NULL,
  `id_zona` bigint UNSIGNED DEFAULT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Comision` int DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `transfer_hotels`
--

INSERT INTO `transfer_hotels` (`id_hotel`, `id_zona`, `nombre`, `usuario`, `Comision`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Hotel Iberostar Alcudia', 'hotel_iberostar', 10, '$2y$12$QpHYYVCfAqrYDXUSpO6DjOkFUdktjnSr2Psm1UqnPtvzY3lju9bVq', 'activo', NULL, NULL),
(2, 2, 'Hotel Meliá Pollensa', 'hotel_melia', 12, '$2y$12$TjrfdT4ry8F3xj1thYjmr.7lrToEwTmJz9sUFXYezvDk3hbswQgp.', 'activo', NULL, NULL),
(3, 3, 'Hotel Riu Cala d\'Or', 'hotel_riu', 10, '$2y$12$achVqT6yyR9xLbwmrzusQenQw1tUU98Z8haRRq7XQzcJG06vb8i7u', 'activo', NULL, NULL),
(4, 4, 'Hotel Hesperia Andratx', 'hotel_hesperia', 15, '$2y$12$g6IBsMolAwrb7wQDKD2uiOU.CIvhqQZvvbuyghPZQ2vSGHAtQT.FG', 'activo', NULL, NULL),
(5, 2, 'Hotel Zaratrustra', 'hotel_zaratrustra', 10, '$2y$12$t/vyUCF2nuY/usuWSE66I.tmqHEzFFLRCEHHO5IiDmag53zQfiQNa', 'activo', '2025-12-17 15:30:51', '2025-12-17 15:30:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transfer_precios`
--

CREATE TABLE `transfer_precios` (
  `id_precios` bigint UNSIGNED NOT NULL,
  `id_vehiculo` bigint UNSIGNED NOT NULL,
  `id_hotel` bigint UNSIGNED NOT NULL,
  `Precio` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `transfer_precios`
--

INSERT INTO `transfer_precios` (`id_precios`, `id_vehiculo`, `id_hotel`, `Precio`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 50, NULL, NULL),
(2, 1, 2, 55, NULL, NULL),
(3, 1, 3, 60, NULL, NULL),
(4, 1, 4, 45, NULL, NULL),
(5, 2, 1, 80, NULL, NULL),
(6, 2, 2, 85, NULL, NULL),
(7, 2, 3, 90, NULL, NULL),
(8, 2, 4, 75, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transfer_reservas`
--

CREATE TABLE `transfer_reservas` (
  `id_reserva` bigint UNSIGNED NOT NULL,
  `localizador` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_hotel` bigint UNSIGNED DEFAULT NULL,
  `id_tipo_reserva` bigint UNSIGNED NOT NULL,
  `email_cliente` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_destino` bigint UNSIGNED NOT NULL,
  `id_vehiculo` bigint UNSIGNED NOT NULL,
  `fecha_reserva` datetime NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `fecha_entrada` date DEFAULT NULL,
  `hora_entrada` time DEFAULT NULL,
  `numero_vuelo_entrada` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origen_vuelo_entrada` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hora_vuelo_salida` time DEFAULT NULL,
  `fecha_vuelo_salida` date DEFAULT NULL,
  `numero_vuelo_salida` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hora_recogida` time DEFAULT NULL,
  `num_viajeros` int NOT NULL,
  `status` enum('pendiente','confirmada','cancelada','completada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `transfer_reservas`
--

INSERT INTO `transfer_reservas` (`id_reserva`, `localizador`, `id_hotel`, `id_tipo_reserva`, `email_cliente`, `id_destino`, `id_vehiculo`, `fecha_reserva`, `fecha_modificacion`, `fecha_entrada`, `hora_entrada`, `numero_vuelo_entrada`, `origen_vuelo_entrada`, `hora_vuelo_salida`, `fecha_vuelo_salida`, `numero_vuelo_salida`, `hora_recogida`, `num_viajeros`, `status`, `created_at`, `updated_at`) VALUES
(1, 'IT-ABC123', NULL, 1, 'ana.garcia@email.com', 1, 1, '2025-11-01 10:00:00', '2025-11-01 10:00:00', '2025-11-10', '14:30:00', 'IB3902', 'Madrid (MAD)', NULL, NULL, NULL, NULL, 2, 'pendiente', NULL, NULL),
(2, 'IT-XYZ789', 3, 3, 'carlos.ruiz@email.com', 3, 2, '2025-11-02 15:00:00', '2025-11-02 15:00:00', '2025-11-12', '09:15:00', 'RYR1001', 'Londres (STN)', '11:00:00', '2025-11-19', 'RYR1002', '08:00:00', 5, 'confirmada', NULL, NULL),
(3, 'LOC-6942C9402C6F1', NULL, 1, 'doplax@gmail.com', 1, 1, '2025-12-17 15:16:16', '2025-12-17 15:16:16', '2025-12-17', '18:15:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'confirmada', '2025-12-17 15:16:16', '2025-12-17 15:16:16'),
(4, 'ADM-C52A12', 1, 1, 'doplax@gmail.com', 4, 1, '2025-12-17 15:33:22', '2025-12-17 15:33:22', '2025-12-18', '12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'confirmada', '2025-12-17 15:33:22', '2025-12-17 15:33:22'),
(5, 'ADM-29FF0A', 3, 1, 'doplax@gmail.com', 4, 1, '2025-12-17 16:05:48', '2025-12-17 16:05:48', '2025-12-19', '12:00:00', NULL, NULL, NULL, NULL, NULL, NULL, 1, 'confirmada', '2025-12-17 16:05:48', '2025-12-17 16:05:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transfer_tipo_reservas`
--

CREATE TABLE `transfer_tipo_reservas` (
  `id_tipo_reserva` bigint UNSIGNED NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `transfer_tipo_reservas`
--

INSERT INTO `transfer_tipo_reservas` (`id_tipo_reserva`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Aeropuerto a Hotel (Llegada)', NULL, NULL),
(2, 'Hotel a Aeropuerto (Salida)', NULL, NULL),
(3, 'Ida y Vuelta (Llegada y Salida)', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transfer_vehiculos`
--

CREATE TABLE `transfer_vehiculos` (
  `id_vehiculo` bigint UNSIGNED NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_conductor` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `transfer_vehiculos`
--

INSERT INTO `transfer_vehiculos` (`id_vehiculo`, `descripcion`, `email_conductor`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Sedan Standard (4 pax)', 'conductor1@islatransfers.com', '$2y$12$5RBchAfUfybmRT.6VErRUeAZ8gzI708V5dJnejTbcxWd4ESNj2fRC', NULL, NULL),
(2, 'Minivan (8 pax)', 'conductor2@islatransfers.com', '$2y$12$F36nxiJbyGn1ttrYY/XqLeyJEHLGjQxUHAEBg69qFQ71lwnxQBQze', NULL, NULL),
(3, 'Vehículo Adaptado (PMR)', 'conductor3@islatransfers.com', '$2y$12$sbTJoyJWpo9Z7B7.2tePauSHjlEjOqBGwsTRchXoyAtuPZx8cNA5W', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transfer_viajeros`
--

CREATE TABLE `transfer_viajeros` (
  `id_viajero` bigint UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido1` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido2` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigoPostal` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pais` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `transfer_viajeros`
--

INSERT INTO `transfer_viajeros` (`id_viajero`, `nombre`, `apellido1`, `apellido2`, `direccion`, `codigoPostal`, `ciudad`, `pais`, `email`, `password`, `status`, `fecha_creacion`, `created_at`, `updated_at`) VALUES
(1, 'Ana', 'García', 'Pérez', 'Avenida Principal 45', '07005', 'Palma', 'España', 'ana.garcia@email.com', '$2y$12$/Kh6VjmBUI/6rwZZ1zzSMutuwYw6clE2b3YMgdHPPUA2jWafM/.Pe', 'activo', '2025-12-17 14:32:01', NULL, NULL),
(2, 'Carlos', 'Ruiz', 'Martínez', 'Paseo Marítimo 10', '07600', 'El Arenal', 'España', 'carlos.ruiz@email.com', '$2y$12$0XVkOh1n1yVZKBfAR5YDz.1Tm3YORjBFMlsH/3ycqqf7tUxCVGCKq', 'activo', '2025-12-17 14:32:01', NULL, NULL),
(3, 'Laura', 'Schmidt', 'Müller', 'Hauptstrasse 15', '10115', 'Berlín', 'Alemania', 'laura.schmidt@email.de', '$2y$12$R54A.BSIDFRljxTSiWAj6.uOFvqjF0Jt22BzMgS5oqMWrAnkOvw6O', 'activo', '2025-12-17 14:32:01', NULL, NULL),
(4, 'doplax', 'pica', 'piedra', '', '', '', '', 'doplax@gmail.com', '$2y$12$.2e4nMbQsB41HJV28USQqOKlDCxAGIfbVszrHEULW6QU7X56gV1uK', 'activo', '2025-12-17 14:33:09', NULL, NULL),
(5, 'admin', '', '', '', '', '', '', 'admin@islatransfers.com', '$2y$12$Vrw6QiOsps2UEIFuaXFeu.llpf2P7C7N1cyL8ZFKDTBRHZT2iZuXO', 'activo', '2025-12-17 15:20:30', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transfer_zonas`
--

CREATE TABLE `transfer_zonas` (
  `id_zona` bigint UNSIGNED NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `transfer_zonas`
--

INSERT INTO `transfer_zonas` (`id_zona`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Palma (Aeropuerto)', NULL, NULL),
(2, 'Zona Norte (Alcudia, Pollensa)', NULL, NULL),
(3, 'Zona Este (Cala d\'Or, Cala Millor)', NULL, NULL),
(4, 'Zona Oeste (Andratx, Paguera)', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indices de la tabla `reserva_admin`
--
ALTER TABLE `reserva_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reserva_admin_id_reserva_foreign` (`id_reserva`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `transfer_hotels`
--
ALTER TABLE `transfer_hotels`
  ADD PRIMARY KEY (`id_hotel`),
  ADD UNIQUE KEY `transfer_hotels_usuario_unique` (`usuario`),
  ADD KEY `transfer_hotels_id_zona_foreign` (`id_zona`);

--
-- Indices de la tabla `transfer_precios`
--
ALTER TABLE `transfer_precios`
  ADD PRIMARY KEY (`id_precios`),
  ADD KEY `transfer_precios_id_vehiculo_foreign` (`id_vehiculo`),
  ADD KEY `transfer_precios_id_hotel_foreign` (`id_hotel`);

--
-- Indices de la tabla `transfer_reservas`
--
ALTER TABLE `transfer_reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `transfer_reservas_id_hotel_foreign` (`id_hotel`),
  ADD KEY `transfer_reservas_id_tipo_reserva_foreign` (`id_tipo_reserva`),
  ADD KEY `transfer_reservas_email_cliente_foreign` (`email_cliente`),
  ADD KEY `transfer_reservas_id_destino_foreign` (`id_destino`),
  ADD KEY `transfer_reservas_id_vehiculo_foreign` (`id_vehiculo`);

--
-- Indices de la tabla `transfer_tipo_reservas`
--
ALTER TABLE `transfer_tipo_reservas`
  ADD PRIMARY KEY (`id_tipo_reserva`);

--
-- Indices de la tabla `transfer_vehiculos`
--
ALTER TABLE `transfer_vehiculos`
  ADD PRIMARY KEY (`id_vehiculo`);

--
-- Indices de la tabla `transfer_viajeros`
--
ALTER TABLE `transfer_viajeros`
  ADD PRIMARY KEY (`id_viajero`),
  ADD UNIQUE KEY `transfer_viajeros_email_unique` (`email`);

--
-- Indices de la tabla `transfer_zonas`
--
ALTER TABLE `transfer_zonas`
  ADD PRIMARY KEY (`id_zona`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reserva_admin`
--
ALTER TABLE `reserva_admin`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transfer_hotels`
--
ALTER TABLE `transfer_hotels`
  MODIFY `id_hotel` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `transfer_precios`
--
ALTER TABLE `transfer_precios`
  MODIFY `id_precios` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `transfer_reservas`
--
ALTER TABLE `transfer_reservas`
  MODIFY `id_reserva` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `transfer_tipo_reservas`
--
ALTER TABLE `transfer_tipo_reservas`
  MODIFY `id_tipo_reserva` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `transfer_vehiculos`
--
ALTER TABLE `transfer_vehiculos`
  MODIFY `id_vehiculo` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `transfer_viajeros`
--
ALTER TABLE `transfer_viajeros`
  MODIFY `id_viajero` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `transfer_zonas`
--
ALTER TABLE `transfer_zonas`
  MODIFY `id_zona` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reserva_admin`
--
ALTER TABLE `reserva_admin`
  ADD CONSTRAINT `reserva_admin_id_reserva_foreign` FOREIGN KEY (`id_reserva`) REFERENCES `transfer_reservas` (`id_reserva`) ON DELETE CASCADE;

--
-- Filtros para la tabla `transfer_hotels`
--
ALTER TABLE `transfer_hotels`
  ADD CONSTRAINT `transfer_hotels_id_zona_foreign` FOREIGN KEY (`id_zona`) REFERENCES `transfer_zonas` (`id_zona`);

--
-- Filtros para la tabla `transfer_precios`
--
ALTER TABLE `transfer_precios`
  ADD CONSTRAINT `transfer_precios_id_hotel_foreign` FOREIGN KEY (`id_hotel`) REFERENCES `transfer_hotels` (`id_hotel`),
  ADD CONSTRAINT `transfer_precios_id_vehiculo_foreign` FOREIGN KEY (`id_vehiculo`) REFERENCES `transfer_vehiculos` (`id_vehiculo`);

--
-- Filtros para la tabla `transfer_reservas`
--
ALTER TABLE `transfer_reservas`
  ADD CONSTRAINT `transfer_reservas_email_cliente_foreign` FOREIGN KEY (`email_cliente`) REFERENCES `transfer_viajeros` (`email`),
  ADD CONSTRAINT `transfer_reservas_id_destino_foreign` FOREIGN KEY (`id_destino`) REFERENCES `transfer_hotels` (`id_hotel`),
  ADD CONSTRAINT `transfer_reservas_id_hotel_foreign` FOREIGN KEY (`id_hotel`) REFERENCES `transfer_hotels` (`id_hotel`),
  ADD CONSTRAINT `transfer_reservas_id_tipo_reserva_foreign` FOREIGN KEY (`id_tipo_reserva`) REFERENCES `transfer_tipo_reservas` (`id_tipo_reserva`),
  ADD CONSTRAINT `transfer_reservas_id_vehiculo_foreign` FOREIGN KEY (`id_vehiculo`) REFERENCES `transfer_vehiculos` (`id_vehiculo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
