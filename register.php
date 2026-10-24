<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

if (!empty($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    redirect('index.php');
}

$username = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (strlen($username) < 3) {
        $errors['username'] = 'Username must be at least 3 characters.';
    } else {
        $stmt = mysqli_prepare($con, 'SELECT id FROM users WHERE username = ?');
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) === 1) {
            $errors['username'] = 'This username is already taken.';
        }
        mysqli_stmt_close($stmt);
    }
    if (strlen($password) < 8) $errors['password'] = 'Password must have at least 8 characters.';
    if ($password !== $confirm) $errors['confirm_password'] = 'Passwords did not match.';

    if (!$errors) {
        $stmt = mysqli_prepare($con, 'INSERT INTO users (username, password) VALUES (?, ?)');
        $hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, 'ss', $username, $hash);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            set_flash('success', 'Account created successfully! Please login.');
            redirect('login.php');
        }
        mysqli_stmt_close($stmt);
        $errors['form'] = 'Oops! Something went wrong. Please try again later.';
    }
}

$pageTitle = 'Create Account';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle) ?> | Student Registration System</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="auth-wrap">
  <div class="auth-side">
    <div class="logo" style="color:#fff"><img src="images/coding-cush-logo.png" alt="" style="width:42px;height:42px;object-fit:contain;background:#fff;border-radius:11px;padding:3px;vertical-align:middle;margin-right:9px">Student<b style="color:#5EEAD4">System</b></div>
    <div>
      <h2>Student Registration System</h2>
      <p>A secure system to register students, manage their records and export data - built with Core PHP &amp; MySQL.</p>
      <ul><li>Secure login with hashed passwords</li><li>Protected student records</li><li>Search, view, edit &amp; export</li></ul>
    </div>
    <small style="opacity:.7">By Coding Cush &bull; Core PHP + MySQL</small>
  </div>
  <div class="auth-main">

    <div class="auth-card">
      <h1>Create Account</h1>
      <p class="sub">Register a new admin/staff account</p>
      <?php if ($f = flash('error')): ?><div class="alert alert-error"><?= e($f) ?></div><?php endif; ?>
      <?php if (isset($errors['form'])): ?><div class="alert alert-error"><?= e($errors['form']) ?></div><?php endif; ?>
      <form method="post" novalidate>
        <?= csrf_field() ?>
        <div class="form-group"><label for="username">Username</label><input id="username" name="username" type="text" value="<?= e($username) ?>" placeholder="Choose a username" required><?php if(isset($errors['username'])):?><div class="error-text"><?= e($errors['username']) ?></div><?php endif; ?></div>
        <div class="form-group"><label for="password">Password</label><div class="input-wrap"><input id="password" name="password" type="password" placeholder="Minimum 8 characters" required><button type="button" class="toggle-pass" data-target="password">Show</button></div><?php if(isset($errors['password'])):?><div class="error-text"><?= e($errors['password']) ?></div><?php endif; ?></div>
        <div class="form-group"><label for="confirm_password">Confirm Password</label><div class="input-wrap"><input id="confirm_password" name="confirm_password" type="password" placeholder="Repeat your password" required><button type="button" class="toggle-pass" data-target="confirm_password">Show</button></div><?php if(isset($errors['confirm_password'])):?><div class="error-text"><?= e($errors['confirm_password']) ?></div><?php endif; ?></div>
        <button class="btn btn-block" type="submit">Create My Account</button>
      </form>
      <p class="auth-foot">Already have an account? <a href="login.php"><strong>Login here</strong></a></p>
    </div>

  </div>
</div>
<script src="assets/js/app.js"></script>
</body>
</html>
