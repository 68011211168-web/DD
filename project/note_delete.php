<?php
require 'config.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    // ลบได้เฉพาะข้อมูลของตัวเองเท่านั้น
    $stmt = $conn->prepare('DELETE FROM notes WHERE id=? AND user_id=?');
    $stmt->bind_param('ii', $id, $_SESSION['user_id']);
    $stmt->execute();
}
header('Location: index.php');
