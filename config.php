<?php
// config.php - Կարգավորումներ
session_start();

// Դատաբեյսի կարգավորումներ
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'news_site');

// Ֆունկցիա ստուգելու համար թե օգտատերը մուտք է գործել
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserInfo() {
    if (isset($_SESSION['user_id'])) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email'] ?? ''
        ];
    }
    return null;
}
?>