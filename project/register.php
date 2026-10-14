<?php
require 'config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === '' || strlen($password) < 4) {
        $err = 'กรุณากรอกชื่อผู้ใช้ และรหัสผ่านอย่างน้อย 4 ตัวอักษร';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT); // bcrypt hashing
        $stmt = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $stmt->bind_param('ss', $username, $hash);
        if ($stmt->execute()) {
            header('Location: login.php?registered=1');
            exit;
        } else {
            $err = 'ชื่อผู้ใช้นี้ถูกใช้แล้ว';
        }
    }
}
include 'header.php';
?>
<h2>สมัครสมาชิก</h2>
<?php if ($err): ?><p class="err"><?= e($err) ?></p><?php endif; ?>
<form method="post" class="card">
  <label>ชื่อผู้ใช้</label>
  <input name="username" required>
  <label>รหัสผ่าน</label>
  <input type="password" name="password" required>
  <button>สมัครสมาชิก</button>
</form>
<?php include 'footer.php'; ?>
