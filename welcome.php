<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_login();

$stmt = mysqli_prepare($con, 'SELECT username, created_at FROM users WHERE id = ?');
$uid = (int)$_SESSION['id'];
mysqli_stmt_bind_param($stmt, 'i', $uid);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $username, $createdAt);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$totalStudents = (int)mysqli_fetch_row(mysqli_query($con, 'SELECT COUNT(*) FROM student_data'))[0];

$pageTitle = 'My Account';
require_once __DIR__ . '/header.php';
?>
<div class="detail-hero">
  <div class="detail-avatar"><?= e(initials($username ?? $_SESSION['username'])) ?></div>
  <div>
    <h1>Hi, <?= e($username ?? $_SESSION['username']) ?> &#128075;</h1>
    <div class="meta">Welcome to the Student Registration System</div>
    <div class="badges"><span class="badge badge-user">Staff Account</span><?php if ($createdAt): ?><span class="badge badge-manager">Member since <?= e(date('d M Y', strtotime($createdAt))) ?></span><?php endif; ?></div>
  </div>
</div>
<div class="stats">
  <div class="stat-card"><small>Total Students Registered</small><div class="num"><?= $totalStudents ?></div></div>
  <div class="stat-card"><small>Your Username</small><div class="num" style="font-size:24px"><?= e($username ?? '') ?></div></div>
</div>
<div class="card">
  <h2>Quick Actions</h2>
  <p style="display:flex;gap:10px;flex-wrap:wrap">
    <a class="btn" href="index.php">View All Students</a>
    <a class="btn btn-amber" href="add.php">+ Add New Student</a>
    <a class="btn btn-light" href="reset-password.php">Reset Your Password</a>
    <a class="btn btn-danger" href="logout.php">Logout</a>
  </p>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
