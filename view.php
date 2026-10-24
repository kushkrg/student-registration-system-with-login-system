<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($con, 'SELECT * FROM student_data WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$s = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
if (!$s) {
    set_flash('error', 'Student not found.');
    redirect('index.php');
}
$age = $s['u_birthday'] ? (int)(new DateTime($s['u_birthday']))->diff(new DateTime())->y : null;
$fullAddress = trim(implode(', ', array_filter([$s['u_village'], $s['u_police'], $s['u_dist'], $s['u_state'], $s['u_pincode']])));

$pageTitle = 'Student Details';
require_once __DIR__ . '/header.php';
?>
<?php if ($msg = flash('success')): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
<div class="detail-hero">
  <?php if (!empty($s['image'])): ?><img class="detail-photo" src="upload_images/<?= e($s['image']) ?>" alt="Student photo">
  <?php else: ?><div class="detail-avatar"><?= e(initials(student_full_name($s))) ?></div><?php endif; ?>
  <div>
    <h1><?= e(student_full_name($s)) ?></h1>
    <div class="meta">Student ID: <?= e($s['u_card']) ?><?= $s['staff_id'] ? ' &bull; Staff ID: ' . e($s['staff_id']) : '' ?></div>
    <div class="meta"><?= e($s['u_email']) ?><?= $s['u_phone'] ? ' &bull; ' . e($s['u_phone']) : '' ?></div>
    <div class="badges"><span class="badge badge-user"><?= e($s['u_gender']) ?></span><?php if ($age !== null): ?><span class="badge badge-manager">Age <?= $age ?></span><?php endif; ?><?php if ($s['uploaded']): ?><span class="badge badge-active">Registered <?= e(date('d M Y', strtotime($s['uploaded']))) ?></span><?php endif; ?></div>
  </div>
</div>
<div class="no-print" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:22px">
  <a class="btn" href="edit.php?id=<?= (int)$s['id'] ?>">Edit Student</a>
  <button class="btn btn-amber" onclick="window.print()">Print Profile</button>
  <a class="btn btn-light" href="index.php">Back to Students</a>
  <form method="post" action="delete.php" data-confirm="Delete this student permanently?" style="display:inline">
    <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
    <button class="btn btn-danger" type="submit">Delete</button>
  </form>
</div>
<div class="detail-grid">
  <div class="card"><h2>Personal Information</h2>
    <dl class="kv">
      <dt>Full Name</dt><dd><?= e(student_full_name($s)) ?></dd>
      <dt>Student ID / Card No</dt><dd><?= e($s['u_card']) ?></dd>
      <dt>Gender</dt><dd><?= e($s['u_gender']) ?></dd>
      <dt>Date of Birth</dt><dd><?= $s['u_birthday'] ? e(date('d M Y', strtotime($s['u_birthday']))) : '-' ?></dd>
      <dt>Age</dt><dd><?= $age !== null ? $age . ' years' : '-' ?></dd>
      <dt>Aadhar No</dt><dd><?= e($s['u_aadhar'] ?: '-') ?></dd>
    </dl>
  </div>
  <div class="card"><h2>Family Information</h2>
    <dl class="kv">
      <dt>Father Name</dt><dd><?= e($s['u_father'] ?: '-') ?></dd>
      <dt>Mother Name</dt><dd><?= e($s['u_mother'] ?: '-') ?></dd>
      <dt>Family Members</dt><dd><?= e($s['u_family'] ?: '-') ?></dd>
    </dl>
  </div>
  <div class="card"><h2>Contact Information</h2>
    <dl class="kv">
      <dt>Email</dt><dd><?= e($s['u_email']) ?></dd>
      <dt>Mobile No</dt><dd><?= e($s['u_phone']) ?></dd>
    </dl>
  </div>
  <div class="card"><h2>Address</h2>
    <dl class="kv">
      <dt>Village / Town</dt><dd><?= e($s['u_village'] ?: '-') ?></dd>
      <dt>Police Station</dt><dd><?= e($s['u_police'] ?: '-') ?></dd>
      <dt>District</dt><dd><?= e($s['u_dist'] ?: '-') ?></dd>
      <dt>State</dt><dd><?= e($s['u_state'] ?: '-') ?></dd>
      <dt>Pincode</dt><dd><?= e($s['u_pincode'] ?: '-') ?></dd>
      <dt>Full Address</dt><dd><?= e($fullAddress ?: '-') ?></dd>
    </dl>
    <hr style="border:none;border-top:1px solid var(--line);margin:20px 0">
    <dl class="kv">
      <dt>Registered On</dt><dd><?= $s['uploaded'] ? e(date('d M Y, h:i A', strtotime($s['uploaded']))) : '-' ?></dd>
      <dt>Registered By (Staff ID)</dt><dd><?= e($s['staff_id'] ?: '-') ?></dd>
    </dl>
  </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
