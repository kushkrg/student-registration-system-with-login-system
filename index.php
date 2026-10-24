<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_login();

$q = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;

// Stats (no user input involved)
$totalStudents = (int)mysqli_fetch_row(mysqli_query($con, 'SELECT COUNT(*) FROM student_data'))[0];
$totalMale = (int)mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM student_data WHERE u_gender = 'Male'"))[0];
$totalFemale = (int)mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM student_data WHERE u_gender = 'Female'"))[0];

// Search + pagination - ALWAYS a prepared statement (the old version built SQL with raw input)
$where = '';
if ($q !== '') {
    $where = ' WHERE u_f_name LIKE ? OR u_l_name LIKE ? OR u_card LIKE ? OR u_phone LIKE ? OR u_email LIKE ?';
}
$countSql = 'SELECT COUNT(*) FROM student_data' . $where;
$countStmt = mysqli_prepare($con, $countSql);
if ($q !== '') {
    $like = '%' . $q . '%';
    mysqli_stmt_bind_param($countStmt, 'sssss', $like, $like, $like, $like, $like);
}
mysqli_stmt_execute($countStmt);
mysqli_stmt_bind_result($countStmt, $filteredTotal);
mysqli_stmt_fetch($countStmt);
mysqli_stmt_close($countStmt);

$totalPages = max(1, (int)ceil($filteredTotal / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$sql = 'SELECT * FROM student_data' . $where . " ORDER BY id DESC LIMIT $perPage OFFSET $offset";
$stmt = mysqli_prepare($con, $sql);
if ($q !== '') {
    $like = '%' . $q . '%';
    mysqli_stmt_bind_param($stmt, 'sssss', $like, $like, $like, $like, $like);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$students = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

$pageTitle = 'All Students';
require_once __DIR__ . '/header.php';
?>
<div class="page-head"><h1>Student Registration System</h1><p>All registered students - search, view, edit and export</p></div>
<?php if ($s = flash('success')): ?><div class="alert alert-success"><?= e($s) ?></div><?php endif; ?>
<?php if ($f = flash('error')): ?><div class="alert alert-error"><?= e($f) ?></div><?php endif; ?>

<div class="stats">
  <div class="stat-card"><small>Total Students</small><div class="num"><?= $totalStudents ?></div></div>
  <div class="stat-card"><small>Male</small><div class="num"><?= $totalMale ?></div></div>
  <div class="stat-card"><small>Female</small><div class="num"><?= $totalFemale ?></div></div>
</div>

<div class="filter-card">
  <form class="search-form" method="get">
    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search by name, student ID, phone or email...">
    <button class="btn" type="submit">Search</button>
    <?php if ($q !== ''): ?><a class="btn btn-light" href="index.php">Reset</a><?php endif; ?>
    <a class="btn btn-amber" href="export.php<?= $q !== '' ? '?q=' . urlencode($q) : '' ?>">Export CSV</a>
    <a class="btn" href="add.php">+ Add New Student</a>
  </form>
</div>

<div class="card">
  <div class="table-wrap"><table>
    <tr><th>Student</th><th>Student ID</th><th>Phone</th><th>Gender</th><th>Staff ID</th><th>Registered</th><th>Actions</th></tr>
    <?php if (!$students): ?><tr><td colspan="7" style="text-align:center;color:var(--muted);padding:28px">No students found. <a href="add.php">Add the first student</a>.</td></tr><?php endif; ?>
    <?php foreach ($students as $row): ?>
    <tr>
      <td><div class="student-cell">
        <?php if (!empty($row['image'])): ?><img class="student-photo" src="upload_images/<?= e($row['image']) ?>" alt="">
        <?php else: ?><span class="student-avatar"><?= e(initials(student_full_name($row))) ?></span><?php endif; ?>
        <span><strong><?= e(student_full_name($row)) ?></strong><br><small style="color:var(--muted)"><?= e($row['u_email']) ?></small></span>
      </div></td>
      <td><?= e($row['u_card']) ?></td>
      <td><?= e($row['u_phone']) ?></td>
      <td><?= e($row['u_gender']) ?></td>
      <td><?= e($row['staff_id']) ?></td>
      <td><?= $row['uploaded'] ? e(date('d M Y', strtotime($row['uploaded']))) : '-' ?></td>
      <td><div class="actions">
        <a class="btn btn-sm btn-view" href="view.php?id=<?= (int)$row['id'] ?>">View</a>
        <a class="btn btn-sm btn-edit" href="edit.php?id=<?= (int)$row['id'] ?>">Edit</a>
        <form method="post" action="delete.php" data-confirm="Delete this student permanently?">
          <?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
          <button class="btn btn-sm btn-danger" type="submit">Delete</button>
        </form>
      </div></td>
    </tr>
    <?php endforeach; ?>
  </table></div>
  <?php if ($totalPages > 1): ?>
  <div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <?php if ($i === $page): ?><span class="current"><?= $i ?></span><?php else: ?><a href="?page=<?= $i ?><?= $q !== '' ? '&q=' . urlencode($q) : '' ?>"><?= $i ?></a><?php endif; ?>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
  <p style="margin-top:14px;color:var(--muted);font-size:14px">Showing <?= count($students) ?> of <?= (int)$filteredTotal ?> student(s)</p>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
