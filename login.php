<?php
// Muhammet Ali ŞAHİN
// github.com/malisahin89

require 'database.php';

session_start([
    'cookie_lifetime' => 1800,  // 30 dakika - saniye cinsinden
    'gc_maxlifetime' => 1800,  // 30 dakika
]);

function validateLogin($email, $password)
{
    $pdo = getDbConnection();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

// Eğer oturum başlamışsa, son erişim zamanını kontrol et
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 60)) {
    // Oturum süresi aşıldı
    session_unset();  // Oturum verilerini temizle
    session_destroy();  // Oturumu yok et
}

// Güncel erişim zamanını güncelle
$_SESSION['LAST_ACTIVITY'] = time();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($email && $password) {
        $user = validateLogin($email, $password);

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Login successful']);
        } else {
            header('HTTP/1.0 401 Unauthorized');
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        }
    } else {
        header('HTTP/1.0 400 Bad Request');
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Email and password are required']);
    }
}
?>