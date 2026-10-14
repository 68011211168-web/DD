<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>บันทึกส่วนตัว</title>
<style>
  body{font-family:'Segoe UI',sans-serif;max-width:800px;margin:30px auto;padding:0 15px;background:#f5f7fa}
  nav{background:#2c3e50;padding:12px 16px;border-radius:8px;margin-bottom:20px}
  nav a{color:#fff;margin-right:14px;text-decoration:none}
  .card{background:#fff;border-radius:8px;padding:16px;margin-bottom:12px;box-shadow:0 1px 4px rgba(0,0,0,.08)}
  input,textarea,select{width:100%;padding:8px;margin:6px 0 12px;border:1px solid #ccc;border-radius:6px;box-sizing:border-box}
  button,.btn{background:#3498db;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;text-decoration:none;display:inline-block}
  .btn.red{background:#e74c3c}.btn.gray{background:#7f8c8d}
  table{width:100%;border-collapse:collapse;background:#fff}
  th,td{border:1px solid #ddd;padding:8px;text-align:left}
  th{background:#ecf0f1}
  .err{color:#e74c3c}
</style>
</head>
<body>
<nav>
  <a href="index.php">หน้าหลัก</a>
  <a href="feedback.php">ส่งข้อความ</a>
  <?php if (($_SESSION['role'] ?? '') === 'admin'): ?><a href="admin.php">ผู้ดูแล</a><?php endif; ?>
  <?php if (isset($_SESSION['user_id'])): ?>
    <a href="logout.php" style="float:right">ออกจากระบบ (<?= e($_SESSION['username']) ?>)</a>
  <?php else: ?>
    <a href="login.php" style="float:right">เข้าสู่ระบบ</a>
    <a href="register.php" style="float:right;margin-right:14px">สมัครสมาชิก</a>
  <?php endif; ?>
</nav>
