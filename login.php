<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

if (!empty($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    redirect('index.php');
}

$username = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter your username and password.';
    } else {
        $stmt = mysqli_prepare($con, 'SELECT id, username, password FROM users WHERE username = ?');
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        $userFound = false;
        if (mysqli_stmt_num_rows($stmt) === 1) {
            mysqli_stmt_bind_result($stmt, $id, $dbUsername, $hashedPassword);
            mysqli_stmt_fetch($stmt);
            $userFound = password_verify($password, $hashedPassword);
        }
        mysqli_stmt_close($stmt);

        if ($userFound) {
            // FIX: regenerate the session ID after login (session fixation protection)
            session_regenerate_id(true);
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['username'] = $dbUsername;
            set_flash('success', 'Welcome back, ' . $dbUsername . '!');
            redirect('index.php');
        }
        // One generic message - never reveal whether the username exists or the password was wrong
        $error = 'Invalid username or password.';
    }
}

$pageTitle = 'Login';
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
      <h1>Admin Login</h1>
      <p class="sub">Login to manage student records</p>
      <?php if ($s = flash('success')): ?><div class="alert alert-success"><?= e($s) ?></div><?php endif; ?>
      <?php if ($f = flash('error')): ?><div class="alert alert-error"><?= e($f) ?></div><?php endif; ?>
      <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
      <form method="post" novalidate>
        <?= csrf_field() ?>
        <div class="form-group"><label for="username">Username</label><input id="username" name="username" type="text" value="<?= e($username) ?>" placeholder="Enter your username" required></div>
        <div class="form-group"><label for="password">Password</label><div class="input-wrap"><input id="password" name="password" type="password" placeholder="Enter your password" required><button type="button" class="toggle-pass" data-target="password">Show</button></div></div>
        <button class="btn btn-block" type="submit">Login</button>
      </form>
      <p class="auth-foot">Don't have an account? <a href="register.php"><strong>Create Account</strong></a></p>
    </div>

  </div>
</div>
<script src="assets/js/app.js"></script>
</body>
</html>
