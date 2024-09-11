# Pure PHP CRUD Login API

Bir elektronik dükkanındaki ürünler üzerinde login olunarak CRUD işlemleri yapan PHP bir uygulama.<br>
"electronicstore" veritabanındaki "goods" tablosuna erişim sağlayan RESTful API'yi içerir.<br>
API, elektronik ürünler için CRUD işlemlerini destekler.<br>

## Proje Geliştirme Ortamım

- **PHP:** `8.3.6`
- **PHP Sunucusu:** `Apache 2.4.59 Laragon üzerinde`
- **Veri Tabanı:** `MySQL 5.7.33`


## Proje Kurulumu

- **Projeyi Klonla:**
  - `git clone https://github.com/malisahin89/pure-php-api-crud-login`

- **Proje Klasörüne eriş:**
  - `cd pure-php-api-crud-login`

- **Veri tabanında "users" tablosunu oluşturun:**
  ```sql
  CREATE TABLE `users` (
      `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
      `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
      `email_verified_at` timestamp NULL DEFAULT NULL,
      `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
      `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
      `created_at` timestamp NULL DEFAULT NULL,
      `updated_at` timestamp NULL DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `users_email_unique` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
  ```

- **Veri tabanında "goods" tablosunu oluşturun:**
  ```sql
  CREATE TABLE goods (
      id INT AUTO_INCREMENT PRIMARY KEY,
      brand VARCHAR(255) NOT NULL,
      type VARCHAR(255) NOT NULL,
      model VARCHAR(20) UNIQUE,
      price DECIMAL(10, 2) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );
  ```
- **api.php dosyasındaki alanı veritabanınıza uygun olarak doldurun:**
  ```php
    $host = 'localhost';
    $db = 'electronicstore';
    $user = 'root';
    $pass = '1234';
    $charset = 'utf8mb4';
  ```
  

- **İsteğe bağlı test verilerini ekleyin (password: 11223344):**
  ```sql
  INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
  (1, 'Test User', 'a@a.com', NULL, '$2y$10$eYNflzBHXlWEhcQ.wwst9OHDArZIZnuM6kGpEQrYagbRAt9VlNg/e', NULL, '2024-09-10 23:19:39', '2024-09-10 23:19:39');

  INSERT INTO goods (brand, type, model, price, created_at) VALUES
  ('Samsung', 'Phone', 'Galaxy S21', 999.99, NOW()),
  ('Apple', 'Phone', 'iPhone 13', 1099.99, NOW()),
  ('Sony', 'TV', 'Bravia X90J', 1499.99, NOW()),
  ('LG', 'TV', 'OLED CX', 1299.99, NOW()),
  ('Dell', 'Laptop', 'XPS 13', 1199.99, NOW()),
  ('HP', 'Laptop', 'Spectre x360', 1099.99, NOW()),
  ('Lenovo', 'Laptop', 'ThinkPad X1', 1399.99, NOW()),
  ('Acer', 'Monitor', 'Predator X34', 799.99, NOW()),
  ('Asus', 'Monitor', 'ROG Swift', 899.99, NOW()),
  ('Bose', 'Headphone', 'QuietComfort 35', 299.99, NOW()),
  ('Sony', 'Headphone', 'WH-1000XM4', 349.99, NOW()),
  ('JBL', 'Soundbar', 'Charge 5', 199.99, NOW()),
  ('Apple', 'Tablet', 'iPad Pro', 999.99, NOW()),
  ('Microsoft', 'Tablet', 'Surface Pro 7', 899.99, NOW()),
  ('Canon', 'Camera', 'EOS R5', 3899.99, NOW()),
  ('Nikon', 'Camera', 'Z7 II', 3499.99, NOW()),
  ('Samsung', 'Smart watch', 'Galaxy Watch 4', 299.99, NOW()),
  ('Apple', 'Smart watch', 'Apple Watch Series 7', 399.99, NOW()),
  ('Garmin', 'Smart watch', 'Fenix 6', 499.99, NOW()),
  ('Fitbit', 'Smart watch', 'Versa 3', 229.99, NOW());
  ```

## API Endpoints

### 1. Tüm Ürünler

- **URL:** `/api.php/goods`
- **Method:** `GET`
- **Description:** Tüm ürünleri listeler.
- **Response:**
  - **200 OK**: Ürünlerin listesini JSON formatında döner.
  - **401 Unauthorized**: Geçersiz API anahtarı.

### 2. Belirli Bir Ürün

- **URL:** `/api.php/goods/{id}`
- **Method:** `GET`
- **Description:** Belirli bir ürünü ID'ye göre getirir.
- **Response:**
  - **200 OK**: Ürün detayları JSON formatında döner.
  - **404 Not Found**: Ürün bulunamazsa hata mesajı döner.
  - **401 Unauthorized**: Geçersiz API anahtarı.

### 3. Yeni Ürün Ekleme

- **URL:** `/api.php/goods`
- **Method:** `POST`
- **Body:** (JSON)
  - `brand` (text): Ürünün markası. (maksimum 255 karakter)
  - `type` (text): Ürünün türü. (maksimum 255 karakter)
  - `model` (text): Ürünün modeli. (20 karakter)
  - `price` (ondalık): Ürünün fiyatı.
  
  ```json
  {
    "brand": "Yeni Ürün",
    "type": "Ürün türü",
    "model": "model kodu",
    "price": 19.99
  }
- **Response:**
  - `201 Created`: Goods created successfully.
  - `400 Bad Request`: Validation failed.
  - `409 Conflict`: Model already exists.
  
### 4. Ürün Düzenleme

- **URL:** `/api.php/goods/{id}`
- **Method:** `PUT`
- **Body:** (JSON)
  - `brand` (text): Ürünün markası. (maksimum 255 karakter)
  - `type` (text): Ürünün türü. (maksimum 255 karakter)
  - `model` (text): Ürünün modeli. (20 karakter)
  - `price` (ondalık): Ürünün fiyatı.
  ```json
  {
    "brand": "Updated name",
    "type": "TV",
    "model": "TV-300",
    "price": 200.99
  }
- **Response:**
  - `200 OK`: Goods updated successfully.
  - `400 Bad Request`: Validation failed.
  - `404 Not Found`: Goods not found or no changes made.
  - `409 Conflict`: Model already exists.
  
### 5. Ürün Silme

- **URL:** `/api.php/goods/{id}`
- **Method:** `DELETE`
- **Response:**
  - `200 OK`: Goods deleted successfully.
  - `404 Not Found`: Goods not found.

## Hata Kodları

- **GOODS_NOT_FOUND:** Ürün bulunamadı.
- **MODEL_EXISTS:** Model zaten mevcut.
- **GOODS_UPDATE_FAILED:** Ürün güncelleme başarısız.
- **GOODS_CREATION_FAILED:** Ürün oluşturma başarısız.
- **METHOD_NOT_ALLOWED:** HTTP yöntemi izin verilmiyor.
