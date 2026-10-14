<?php
require 'config.php';
require_login();
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $body = trim($_POST['body'] ?? '');
    if ($subject === '' || $body === '') {
        $err = 'กรุณากรอกให้ครบ';
    } else {
        $stmt = $conn->prepare('INSERT INTO messages (user_id, subject, body) VALUES (?, ?, ?)');
        $stmt->bind_param('iss', $_SESSION['user_id'], $subject, $body);
        $stmt->execute();
        header('Location: feedback.php?sent=1');
        exit;
    }
}
$stmt = $conn->prepare('SELECT m.*, u.username FROM messages m JOIN users u ON u.id=m.user_id WHERE m.user_id=? ORDER BY m.created_at DESC');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$msgs = $stmt->get_result();
include 'header.php';
?>
<h2>แบบฟอร์มส่งข้อความถึงผู้ดูแล</h2>
<?php if (isset($_GET['sent'])): ?><p style="color:green">ส่งข้อความเรียบร้อย</p><?php endif; ?>
<?php if ($err): ?><p class="err"><?= e($err) ?></p><?php endif; ?>
<form method="post" class="card">
  <label>หัวข้อ</label>
  <input name="subject" required>
  <label>ข้อความ</label>
  <textarea name="body" rows="5" required></textarea>
  <button>ส่งข้อความ</button>
</form>
<h3>ข้อความของฉัน</h3>
<?php while ($m = $msgs->fetch_assoc()): ?>
  <div class="card">
    <strong><?= e($m['subject']) ?></strong> <small>(<?= e($m['created_at']) ?>)</small>
    <p><?= nl2br(e($m['body'])) ?></p>
  </div>
<?php endwhile; ?>
<?php include 'footer.php'; ?>
