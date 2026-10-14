<?php
require 'config.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $conn->prepare('SELECT * FROM users WHERE username=?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit;
    }
    $err = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
}
include 'header.php';
?>
<h2>เข้าสู่ระบบ</h2>
<h6>ลองเข้าแอดมินด้วย admin/admin1234</h6>
<?php if (isset($_GET['registered'])): ?><p style="color:green">สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ</p><?php endif; ?>
<?php if ($err): ?><p class="err"><?= e($err) ?></p><?php endif; ?>
<form method="post" class="card">
  <label>ชื่อผู้ใช้ </label>
  <input name="username" required>
  <label>รหัสผ่าน</label>
  <input type="password" name="password" required>
  <button>เข้าสู่ระบบ</button>
</form>
<?php include 'footer.php'; ?>
