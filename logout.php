<?php
// Muhammet Ali ŞAHİN
// github.com/malisahin89

session_start();  // Mevcut oturumu başlat

// Oturumu temizle
$_SESSION = array();

// Oturumu yok et
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']);
}

// Oturumu yok et
session_destroy();

// Başarı mesajı veya yönlendirme
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Logout successful']);
