<?php
// Muhammet Ali ŞAHİN
// github.com/malisahin89

// CREATE TABLE goods (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     brand VARCHAR(255) NOT NULL,
//     type VARCHAR(255) NOT NULL,
//     model VARCHAR(20) UNIQUE,
//     price DECIMAL(10, 2) NOT NULL,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
//     );
// INSERT INTO goods (brand, type, model, price, created_at) VALUES
// ('Samsung', 'Phone', 'Galaxy S21', 999.99, NOW()),
// ('Apple', 'Phone', 'iPhone 13', 1099.99, NOW()),
// ('Sony', 'TV', 'Bravia X90J', 1499.99, NOW()),
// ('LG', 'TV', 'OLED CX', 1299.99, NOW()),
// ('Dell', 'Laptop', 'XPS 13', 1199.99, NOW()),
// ('HP', 'Laptop', 'Spectre x360', 1099.99, NOW()),
// ('Lenovo', 'Laptop', 'ThinkPad X1', 1399.99, NOW()),
// ('Acer', 'Monitor', 'Predator X34', 799.99, NOW()),
// ('Asus', 'Monitor', 'ROG Swift', 899.99, NOW()),
// ('Bose', 'Headphone', 'QuietComfort 35', 299.99, NOW()),
// ('Sony', 'Headphone', 'WH-1000XM4', 349.99, NOW()),
// ('JBL', 'Soundbar', 'Charge 5', 199.99, NOW()),
// ('Apple', 'Tablet', 'iPad Pro', 999.99, NOW()),
// ('Microsoft', 'Tablet', 'Surface Pro 7', 899.99, NOW()),
// ('Canon', 'Camera', 'EOS R5', 3899.99, NOW()),
// ('Nikon', 'Camera', 'Z7 II', 3499.99, NOW()),
// ('Samsung', 'Smart watch', 'Galaxy Watch 4', 299.99, NOW()),
// ('Apple', 'Smart watch', 'Apple Watch Series 7', 399.99, NOW()),
// ('Garmin', 'Smart watch', 'Fenix 6', 499.99, NOW()),
// ('Fitbit', 'Smart watch', 'Versa 3', 229.99, NOW());


// CREATE TABLE `users` (
//     `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
//     `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
//     `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
//     `email_verified_at` timestamp NULL DEFAULT NULL,
//     `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
//     `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
//     `created_at` timestamp NULL DEFAULT NULL,
//     `updated_at` timestamp NULL DEFAULT NULL,
//     PRIMARY KEY (`id`),
//     UNIQUE KEY `users_email_unique` (`email`)
//   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
// // Email: a@a.com
// // Password; 11223344
// INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
// (1, 'Test User', 'a@a.com', NULL, '$2y$10$eYNflzBHXlWEhcQ.wwst9OHDArZIZnuM6kGpEQrYagbRAt9VlNg/e', NULL, '2024-09-10 23:19:39', '2024-09-10 23:19:39');

session_start();
require 'database.php';

function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

// Ana uygulama mantığı
$method = $_SERVER['REQUEST_METHOD'];
$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
$request = explode('/', trim($path_info, '/'));
$input = json_decode(file_get_contents('php://input'), true);

if (!isAuthenticated()) {
    header('HTTP/1.0 401 Unauthorized');
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => [
            'code' => 'UNAUTHORIZED',
            'message' => 'User not authenticated.',
        ],
    ]);
    exit();
}

// Veritabanına göre verileri kontrol etme
function isDecimal($value) { return is_numeric($value) && ($value == (int) $value || $value == (float) $value); }

function validateBrand($brand) { return !empty($brand) && strlen($brand) <= 255; }

function validateType($type) { return !empty($type) && strlen($type) <= 255; }

function validateModel($model) { return !empty($model) && strlen($model) <= 20; }

function secureInput($data) { return htmlspecialchars(strip_tags($data)); }

// Router
$pdo = getDbConnection();
switch ($method) {
    // GET Metodu
    case 'GET':
        if (isset($request[0]) && $request[0] === 'goods') {
            if (isset($request[1])) {
                $id = $request[1];
                $stmt = $pdo->prepare('SELECT * FROM goods WHERE id = ?');
                $stmt->execute([$id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'data' => $result,
                    ]);
                } else {
                    header('HTTP/1.0 404 Not Found');
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'error' => [
                            'code' => 'GOODS_NOT_FOUND',
                            'message' => 'Good not found.',
                        ],
                    ]);
                }
            } else {
                $sql = 'SELECT * FROM goods WHERE 1=1';
                $params = [];

                if (isset($_GET['brand'])) {
                    $sql .= ' AND brand LIKE ?';
                    $params[] = '%' . secureInput($_GET['brand']) . '%';
                }

                if (isset($_GET['type'])) {
                    $sql .= ' AND type LIKE ?';
                    $params[] = secureInput($_GET['type']);
                }

                if (isset($_GET['model'])) {
                    $sql .= ' AND model = ?';
                    $params[] = secureInput($_GET['model']);
                }

                if (isset($_GET['min_price'])) {
                    $sql .= ' AND price >= ?';
                    $params[] = secureInput($_GET['min_price']);
                }

                if (isset($_GET['max_price'])) {
                    $sql .= ' AND price <= ?';
                    $params[] = secureInput($_GET['max_price']);
                }

                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($result) == 0) {
                    $result[] = ['message' => 'Specified criteria does not exist.'];
                }

                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'data' => $result,
                ]);
            }
        }
        break;

    case 'POST':
        if ($request[0] === 'goods') {
            $brand = isset($input['brand']) ? secureInput($input['brand']) : null;
            $type = isset($input['type']) ? secureInput($input['type']) : null;
            $model = isset($input['model']) ? secureInput($input['model']) : null;
            $price = isset($input['price']) ? secureInput($input['price']) : null;

            // İnputları Doğrulama
            $errors = [];
            if (!validateBrand($brand)) {
                $errors[] = ['field' => 'brand', 'message' => 'Invalid brand.'];
            }

            if (!validateType($type)) {
                $errors[] = ['field' => 'type', 'message' => 'Invalid type.'];
            }

            if (!validateModel($model)) {
                $errors[] = ['field' => 'model', 'message' => 'Invalid model.'];
            }

            if ($price === null || !isDecimal($price)) {
                $errors[] = ['field' => 'price', 'message' => 'Price must be a number or decimal value.'];
            }

            if (!empty($errors)) {
                header('HTTP/1.0 400 Bad Request');
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'errors' => $errors,
                ]);
                exit();
            }

            try {
                $stmt = $pdo->prepare('SELECT * FROM goods WHERE model = ?');
                $stmt->execute([$model]);

                if ($stmt->fetch()) {
                    header('HTTP/1.0 409 Conflict');
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'error' => [
                            'code' => 'MODEL_EXISTS',
                            'message' => 'Model already exists.',
                        ],
                    ]);
                    exit();
                }

                $stmt = $pdo->prepare('INSERT INTO goods (brand, type, model, price) VALUES (:brand, :type, :model, :price)');
                $stmt->bindParam(':brand', $brand);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':model', $model);
                $stmt->bindParam(':price', $price);
                if ($stmt->execute()) {
                    header('HTTP/1.0 201 Created');
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'id' => $pdo->lastInsertId(),
                            'message' => 'Good created successfully.',
                        ],
                    ]);
                } else {
                    header('HTTP/1.0 500 Internal Server Error');
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'error' => [
                            'code' => 'GOODS_CREATION_FAILED',
                            'message' => 'Failed to add good.',
                        ],
                    ]);
                }
            } catch (PDOException $e) {
                header('HTTP/1.0 500 Internal Server Error');
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => 'DATABASE_ERROR',
                        'message' => 'An error occurred while processing your request.',
                    ],
                ]);
            }
        }
        break;

    case 'PUT':
        if ($request[0] === 'goods' && isset($request[1])) {
            $id = (int) $request[1];
            $data = json_decode(file_get_contents('php://input'), true);

            if (!isset($data['brand'], $data['type'], $data['model'], $data['price'])) {
                header('HTTP/1.0 400 Bad Request');
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => 'INVALID_INPUT',
                        'message' => 'All fields (brand, type, model, price) are required.',
                    ],
                ]);
                exit();
            }

            $brand = secureInput($data['brand']);
            $type = secureInput($data['type']);
            $model = secureInput($data['model']);
            $price = secureInput($data['price']);

            $errors = [];
            if (!validateBrand($brand)) {
                $errors[] = ['field' => 'brand', 'message' => 'Invalid brand.'];
            }

            if (!validateType($type)) {
                $errors[] = ['field' => 'type', 'message' => 'Invalid type.'];
            }

            if (!validateModel($model)) {
                $errors[] = ['field' => 'model', 'message' => 'Invalid model.'];
            }

            if ($price === null || !isDecimal($price)) {
                $errors[] = ['field' => 'price', 'message' => 'Price must be a number or decimal value.'];
            }

            if (!empty($errors)) {
                header('HTTP/1.0 400 Bad Request');
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'errors' => $errors,
                ]);
                exit();
            }

            try {
                $stmt = $pdo->prepare('SELECT * FROM goods WHERE model = ? AND id != ?');
                $stmt->execute([$model, $id]);

                if ($stmt->fetch()) {
                    header('HTTP/1.0 409 Conflict');
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'error' => [
                            'code' => 'MODEL_EXISTS',
                            'message' => 'Model already exists.',
                        ],
                    ]);
                    exit();
                }

                $stmt = $pdo->prepare('UPDATE goods SET brand = :brand, type = :type, model = :model, price = :price WHERE id = :id');
                $stmt->bindParam(':brand', $brand);
                $stmt->bindParam(':type', $type);
                $stmt->bindParam(':model', $model);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':id', $id);

                if ($stmt->execute()) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'message' => 'Good updated successfully.',
                        ],
                    ]);
                } else {
                    header('HTTP/1.0 500 Internal Server Error');
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'error' => [
                            'code' => 'GOODS_UPDATE_FAILED',
                            'message' => 'Failed to update good.',
                        ],
                    ]);
                }
            } catch (PDOException $e) {
                header('HTTP/1.0 500 Internal Server Error');
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => 'DATABASE_ERROR',
                        'message' => 'An error occurred while processing your request.',
                    ],
                ]);
            }
        }
        break;

    case 'DELETE':
        if ($request[0] === 'goods' && isset($request[1])) {
            $id = (int) $request[1];

            try {
                $stmt = $pdo->prepare('DELETE FROM goods WHERE id = ?');
                $stmt->execute([$id]);

                if ($stmt->rowCount() > 0) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'data' => [
                            'message' => 'Good deleted successfully.',
                        ],
                    ]);
                } else {
                    header('HTTP/1.0 404 Not Found');
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'error' => [
                            'code' => 'GOODS_NOT_FOUND',
                            'message' => 'Good not found.',
                        ],
                    ]);
                }
            } catch (PDOException $e) {
                header('HTTP/1.0 500 Internal Server Error');
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => 'DATABASE_ERROR',
                        'message' => 'An error occurred while processing your request.',
                    ],
                ]);
            }
        }
        break;

    default:
        header('HTTP/1.0 405 Method Not Allowed');
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => [
                'code' => 'METHOD_NOT_ALLOWED',
                'message' => 'Method not allowed.',
            ],
        ]);
        break;
}
