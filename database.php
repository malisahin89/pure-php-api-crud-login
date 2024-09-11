<?php
// Muhammet Ali ŞAHİN
// github.com/malisahin89

// Veritabanı bağlantısı için yapı
function getDbConnection()
{
    $host = 'localhost';  // Veritabanı sunucusu
    $db = 'bookstore';  // Veritabanı adı
    $user = 'root';  // Veritabanı kullanıcı adı
    $pass = '1234';  // Veritabanı şifresi
    $charset = 'utf8mb4';  // Karakter seti
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";  // DSN (Data Source Name)
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Hata modunu istisna olarak ayarla
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Varsayılan veri alma modunu ilişkisel dizgi olarak ayarla
        PDO::ATTR_EMULATE_PREPARES => false,  // Yerleşik SQL prepare işlevlerini kullan
    ];

    try {
        // Veritabanı bağlantısını oluştur ve döndür
        return new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        // Hata durumunda loglama ve hata yanıtı
        error_log($e->getMessage());
        header('HTTP/1.0 500 Internal Server Error');
        echo json_encode([
            'success' => false,
            'error' => [
                'code' => 'DATABASE_CONNECTION_FAILED',
                'message' => 'Failed to connect to the database.',
            ],
        ]);
        exit();
    }
}
?>
