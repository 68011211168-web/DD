
<?php
require_once __DIR__ . '/config.php';
require_login();

$uid = (int)$_SESSION['user_id'];
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

$stmt = $conn->prepare(
    'SELECT n.*, u.username
     FROM notes n
     JOIN users u ON u.id = n.user_id
     ORDER BY n.created_at DESC'
);

$stmt->execute();
$notes = $stmt->get_result();

include 'header.php';
?>

<a class="btn" href="note_form.php">+ เพิ่มบันทึกใหม่</a>
<h2>บันทึกทั้งหมด</h2>

<?php if ($notes->num_rows === 0): ?>
  <div class="card">ยังไม่มีบันทึก</div>
<?php endif; ?>

<?php while ($n = $notes->fetch_assoc()): ?>
  <div class="card">
    <h3><?= e($n['title']) ?></h3>

    <p>
      <?= nl2br(e($n['content'])) ?>
    </p>

    <p>
      <strong>เจ้าของโน้ต:</strong>
      <?= e($n['username']) ?>
    </p>

    <small>วันที่บันทึก: <?= e($n['created_at']) ?></small>
    <br><br>

      <a class="btn gray"
         href="note_form.php?id=<?= (int)$n['id'] ?>">
        แก้ไข
      </a>

      <form method="post"
            action="note_delete.php"
            style="display:inline"
            onsubmit="return confirm('ยืนยันการลบโน้ตนี้หรือไม่?')">

        <input type="hidden"
               name="id"
               value="<?= (int)$n['id'] ?>">

        <button class="btn red" type="submit">ลบ</button>
      </form>
  </div>
<?php endwhile; ?>

<?php include 'footer.php'; ?>
