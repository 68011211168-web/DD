<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'miniapp';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die('เชื่อมต่อฐานข้อมูลไม่สำเร็จ: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

if (!function_exists('require_login')) {
    function require_login() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }
    }
}

if (!function_exists('require_admin')) {
function require_admin() {
    require_login();
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        die('<h2>403 - สำหรับผู้ดูแลระบบเท่านั้น</h2>');
        }
    }
}

if (!function_exists('e')) {
function e($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
}
?>
