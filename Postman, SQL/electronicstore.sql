-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 11 Eyl 2024, 01:04:10
-- Sunucu sürümü: 5.7.33
-- PHP Sürümü: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `electronicstore`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `goods`
--

INSERT INTO `goods` (`id`, `brand`, `type`, `model`, `price`, `created_at`) VALUES
(1, 'Samsung', 'Phone', 'Galaxy S21', 999.99, '2024-09-11 01:01:43'),
(2, 'Apple', 'Phone', 'iPhone 13', 1099.99, '2024-09-11 01:01:43'),
(3, 'Sony', 'TV', 'Bravia X90J', 1499.99, '2024-09-11 01:01:43'),
(4, 'LG', 'TV', 'OLED CX', 1299.99, '2024-09-11 01:01:43'),
(5, 'Dell', 'Laptop', 'XPS 13', 1199.99, '2024-09-11 01:01:43'),
(6, 'HP', 'Laptop', 'Spectre x360', 1099.99, '2024-09-11 01:01:43'),
(7, 'Lenovo', 'Laptop', 'ThinkPad X1', 1399.99, '2024-09-11 01:01:43'),
(8, 'Acer', 'Monitor', 'Predator X34', 799.99, '2024-09-11 01:01:43'),
(9, 'Asus', 'Monitor', 'ROG Swift', 899.99, '2024-09-11 01:01:43'),
(10, 'Bose', 'Headphone', 'QuietComfort 35', 299.99, '2024-09-11 01:01:43'),
(11, 'Sony', 'Headphone', 'WH-1000XM4', 349.99, '2024-09-11 01:01:43'),
(12, 'JBL', 'Soundbar', 'Charge 5', 199.99, '2024-09-11 01:01:43'),
(13, 'Apple', 'Tablet', 'iPad Pro', 999.99, '2024-09-11 01:01:43'),
(14, 'Microsoft', 'Tablet', 'Surface Pro 7', 899.99, '2024-09-11 01:01:43'),
(15, 'Canon', 'Camera', 'EOS R5', 3899.99, '2024-09-11 01:01:43'),
(16, 'Nikon', 'Camera', 'Z7 II', 3499.99, '2024-09-11 01:01:43'),
(17, 'Samsung', 'Smart watch', 'Galaxy Watch 4', 299.99, '2024-09-11 01:01:43'),
(18, 'Apple', 'Smart watch', 'Apple Watch Series 7', 399.99, '2024-09-11 01:01:43'),
(19, 'Garmin', 'Smart watch', 'Fenix 6', 499.99, '2024-09-11 01:01:43'),
(20, 'Fitbit', 'Smart watch', 'Versa 3', 229.99, '2024-09-11 01:01:43');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'a@a.com', NULL, '$2y$10$eYNflzBHXlWEhcQ.wwst9OHDArZIZnuM6kGpEQrYagbRAt9VlNg/e', NULL, '2024-09-10 20:19:39', '2024-09-10 20:19:39');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `model` (`model`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
