<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_login(); // FIX: the old delete.php had NO login check and deleted via a GET link

// FIX: delete is POST-only with a CSRF token (a GET link can be triggered by any website / prefetch)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}
verify_csrf();

$id = (int)($_POST['id'] ?? 0);

// Fetch the photo first so its file can be removed with the record
$stmt = mysqli_prepare($con, 'SELECT image FROM student_data WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $image);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$stmt = mysqli_prepare($con, 'DELETE FROM student_data WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if (!empty($image)) {
    $file = __DIR__ . '/upload_images/' . $image;
    if (is_file($file)) unlink($file);
}
set_flash('success', 'Student deleted successfully.');
redirect('index.php');
