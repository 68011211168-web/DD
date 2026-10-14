<?php
require 'config.php';
require_admin();

// ลบผู้ใช้ (ต้องไม่ใช่ตัวเอง)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $id = (int)$_POST['delete_user'];
    if ($id !== (int)$_SESSION['user_id']) {
        $stmt = $conn->prepare('DELETE FROM users WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}
// ลบโน้ตของใครก็ได้ (สิทธิ์แอดมิน)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_note'])) {
    $id = (int)$_POST['delete_note'];
    $stmt = $conn->prepare('DELETE FROM notes WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

$users = $conn->query('SELECT id, username, role, created_at FROM users ORDER BY id');
$notes = $conn->query('SELECT n.*, u.username FROM notes n JOIN users u ON u.id=n.user_id ORDER BY n.created_at DESC');
$msgs  = $conn->query('SELECT m.*, u.username FROM messages m JOIN users u ON u.id=m.user_id ORDER BY m.created_at DESC');
$totalUsers = $users->num_rows;
include 'header.php';
?>
<h2>หน้าผู้ดูแลระบบ (Admin)</h2>
<div class="card">จำนวนผู้ใช้ทั้งหมด: <?= $totalUsers ?> | โน้ตทั้งหมด: <?= $notes->num_rows ?> | ข้อความ: <?= $msgs->num_rows ?></div>

<h3>ผู้ใช้งาน</h3>
<table>
  <tr><th>ID</th><th>ชื่อผู้ใช้</th><th>บทบาท</th><th>สมัครเมื่อ</th><th>จัดการ</th></tr>
  <?php $users->data_seek(0); while ($u = $users->fetch_assoc()): ?>
  <tr>
    <td><?= $u['id'] ?></td><td><?= e($u['username']) ?></td><td><?= e($u['role']) ?></td><td><?= e($u['created_at']) ?></td>
    <td>
      <?php if ((int)$u['id'] !== (int)$_SESSION['user_id']): ?>
      <form method="post" style="display:inline" onsubmit="return confirm('ลบผู้ใช้นี้?')">
        <input type="hidden" name="delete_user" value="<?= $u['id'] ?>">
        <button class="btn red">ลบ</button>
      </form>
      <?php endif; ?>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

<h3>โน้ตทั้งหมด</h3>
<table>
  <tr>
    <th>หัวข้อ</th>
    <th>เนื้อหา</th>
    <th>เจ้าของ</th>
    <th>วันที่</th>
    <th>จัดการ</th>
  </tr>

  <?php $notes->data_seek(0); while ($n = $notes->fetch_assoc()): ?>
  <tr>
    <td><?= e($n['title']) ?></td>
    <td><?= nl2br(e($n['content'])) ?></td>
    <td><?= e($n['username']) ?></td>
    <td><?= e($n['created_at']) ?></td>
    <td>
      <form method="post"
            style="display:inline"
            onsubmit="return confirm('ลบโน้ตนี้?')">
        <input type="hidden"
               name="delete_note"
               value="<?= (int)$n['id'] ?>">
        <button class="btn red" type="submit">ลบ</button>
      </form>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

<h3>ข้อความจากผู้ใช้</h3>
<?php $msgs->data_seek(0); while ($m = $msgs->fetch_assoc()): ?>
  <div class="card"><strong><?= e($m['subject']) ?></strong> — จาก <?= e($m['username']) ?> (<?= e($m['created_at']) ?>)<p><?= nl2br(e($m['body'])) ?></p></div>
<?php endwhile; ?>
<?php include 'footer.php'; ?>
