<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_login();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if (strlen($new) < 8) $errors['new_password'] = 'Password must have at least 8 characters.';
    if ($new !== $confirm) $errors['confirm_password'] = 'Passwords did not match.';
    if (!$errors) {
        $stmt = mysqli_prepare($con, 'UPDATE users SET password = ? WHERE id = ?');
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $uid = (int)$_SESSION['id'];
        mysqli_stmt_bind_param($stmt, 'si', $hash, $uid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        // Log the user out everywhere after a password change, then send them to login
        $_SESSION = [];
        session_destroy();
        session_start();
        set_flash('success', 'Password reset successfully. Please login with your new password.');
        redirect('login.php');
    }
}
$pageTitle = 'Reset Password';
require_once __DIR__ . '/header.php';
?>
<div class="page-head"><h1>Reset Password</h1><p>Set a new password for your account</p></div>
<?php if (isset($errors['form'])): ?><div class="alert alert-error"><?= e($errors['form']) ?></div><?php endif; ?>
<div class="card" style="max-width:560px">
  <form method="post" novalidate>
    <?= csrf_field() ?>
    <div class="form-group"><label for="new_password">New Password</label><div class="input-wrap"><input id="new_password" name="new_password" type="password" placeholder="Minimum 8 characters" required><button type="button" class="toggle-pass" data-target="new_password">Show</button></div><?php if(isset($errors['new_password'])):?><div class="error-text"><?= e($errors['new_password']) ?></div><?php endif; ?></div>
    <div class="form-group"><label for="confirm_password">Confirm Password</label><div class="input-wrap"><input id="confirm_password" name="confirm_password" type="password" placeholder="Repeat the new password" required><button type="button" class="toggle-pass" data-target="confirm_password">Show</button></div><?php if(isset($errors['confirm_password'])):?><div class="error-text"><?= e($errors['confirm_password']) ?></div><?php endif; ?></div>
    <button class="btn" type="submit">Reset Password</button>
    <a class="btn btn-light" href="welcome.php">Cancel</a>
  </form>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
