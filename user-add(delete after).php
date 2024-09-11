<?php
// Muhammet Ali ŞAHİN
// github.com/malisahin89

require 'database.php';

// Kullanıcı bilgileri
$name = 'Test User';
$email = 'a@a.com';
$password = '11223344';

// Şifreyi hashleyin
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// SQL sorgusu
$sql = "INSERT INTO users (name, email, password, email_verified_at, remember_token, created_at, updated_at)
        VALUES (?, ?, ?, NULL, NULL, NOW(), NOW())";

// Veritabanı bağlantısını oluşturun ve sorguyu çalıştırın
$pdo = getDbConnection();
$stmt = $pdo->prepare($sql);
$stmt->execute([$name, $email, $hashedPassword]);

echo "Kullanıcı başarıyla eklendi.";
?>
