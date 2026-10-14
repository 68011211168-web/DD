<?php
require 'config.php';
require_login();
$uid = $_SESSION['user_id'];
$id = (int)($_GET['id'] ?? 0);
$note = ['title' => '', 'content' => ''];

// โหลดเฉพาะบันทึกที่เป็นของตัวเองเท่านั้น

if ($id) {
    $stmt = $conn->prepare('SELECT * FROM notes WHERE id=? AND user_id=?');
    $stmt->bind_param('ii', $id, $uid);
    $stmt->execute();
    $note = $stmt->get_result()->fetch_assoc();
    if (!$note) { http_response_code(403); die('ไม่พบข้อมูล หรือไม่ใช่เจ้าของข้อมูล'); }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if ($title === '' || $content === '') {
        $err = 'กรุณากรอกให้ครบ';
    } else {
        if ($id) {
            $stmt = $conn->prepare('UPDATE notes SET title=?, content=? WHERE id=? AND user_id=?');
            $stmt->bind_param('ssii', $title, $content, $id, $uid);
        } else {
            $stmt = $conn->prepare('INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)');
            $stmt->bind_param('iss', $uid, $title, $content);
        }
        $stmt->execute();
        header('Location: index.php');
        exit;
    }
}
include 'header.php';
?>
<h2><?= $id ? 'แก้ไขบันทึก' : 'เพิ่มบันทึกใหม่' ?></h2>
<?php if (!empty($err)): ?><p class="err"><?= e($err) ?></p><?php endif; ?>
<form method="post" class="card">
  <label>หัวข้อ</label>
  <input name="title" value="<?= e($note['title']) ?>" required>
  <label>เนื้อหา</label>
  <textarea name="content" rows="6" required><?= e($note['content']) ?></textarea>
  <button>บันทึก</button>
  <a class="btn gray" href="index.php">ยกเลิก</a>
</form>
<?php include 'footer.php'; ?>
