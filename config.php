<?php
session_start();

$host = 'localhost:3307';
$user = 'root';
$pass = '';
$db = 'kirana_shop';

$conn = new mysqli($host, $user, $pass, $db);

if($conn->connect_error) die("Connection failed: " . $conn->connect_error);

mysqli_set_charset($conn, "utf8mb4");

define('SITE_URL', 'http://localhost/kirana-shop/');
define('UPLOAD_DIR', __DIR__ . '/uploads/');

function redirect($url) {
    header("Location: " . SITE_URL . $url);
    exit;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function checkLogin() {
    if(!isLoggedIn()) redirect('index.php');
}
?>