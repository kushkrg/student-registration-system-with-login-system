<?php
// Shared helpers: secure session, escaping, CSRF, flash messages, auth guard, uploads, validation.
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true,
    ]);
}

function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void {
    if (!isset($_POST['csrf_token']) || !hash_equals(csrf_token(), (string)$_POST['csrf_token'])) {
        set_flash('error', 'Invalid security token. Please try again.');
        redirect('index.php');
    }
}

function set_flash(string $type, string $message): void {
    $_SESSION['flash_' . $type] = $message;
}

function flash(string $type): ?string {
    if (!empty($_SESSION['flash_' . $type])) {
        $msg = $_SESSION['flash_' . $type];
        unset($_SESSION['flash_' . $type]);
        return $msg;
    }
    return null;
}

// Same session keys as the original version (loggedin / id / username) for compatibility.
function require_login(): void {
    if (empty($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        redirect('login.php');
    }
}

function initials(string $name): string {
    $out = '';
    foreach (array_slice(preg_split('/\s+/', trim($name)), 0, 2) as $part) {
        $out .= strtoupper(mb_substr($part, 0, 1));
    }
    return $out ?: 'S';
}

function student_full_name(array $row): string {
    return trim(($row['u_f_name'] ?? '') . ' ' . ($row['u_l_name'] ?? ''));
}

/**
 * Secure student photo upload.
 * FIX (old version): ANY file type could be uploaded with its original name into a
 * web-accessible folder (a PHP shell upload = full server takeover), and editing a
 * student without choosing a new photo silently wiped the saved photo.
 * Now: JPG/PNG/WEBP only, 2 MB max, random file name, old photo kept on edit.
 * Returns [filename|null, error|null].
 */
function handle_image_upload(string $inputName = 'image', ?string $oldImage = null): array {
    if (empty($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
        return [$oldImage, null];
    }
    $file = $_FILES[$inputName];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [null, 'Photo upload failed. Please try again.'];
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        return [null, 'Photo must be under 2 MB.'];
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
        return [null, 'Photo must be a JPG, PNG or WEBP image.'];
    }
    $dir = __DIR__ . '/../upload_images/';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $name = 'student_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $dir . $name)) {
        return [null, 'Could not save the photo.'];
    }
    return [$name, null];
}

/**
 * Validate the student form (used by add.php and edit.php).
 * POST field names are unchanged from the original version.
 * Returns [data, errors] where data maps to student_data columns.
 */
function validate_student(array $post): array {
    $data = [
        'u_card'    => trim($post['card_no'] ?? ''),
        'u_f_name'  => trim($post['user_first_name'] ?? ''),
        'u_l_name'  => trim($post['user_last_name'] ?? ''),
        'u_father'  => trim($post['user_father'] ?? ''),
        'u_mother'  => trim($post['user_mother'] ?? ''),
        'u_aadhar'  => trim($post['user_aadhar'] ?? ''),
        'u_birthday'=> trim($post['user_dob'] ?? ''),
        'u_gender'  => trim($post['user_gender'] ?? ''),
        'u_email'   => trim($post['user_email'] ?? ''),
        'u_phone'   => trim($post['user_phone'] ?? ''),
        'u_state'   => trim($post['state'] ?? ''),
        'u_dist'    => trim($post['dist'] ?? ''),
        'u_village' => trim($post['village'] ?? ''),
        'u_police'  => trim($post['police_station'] ?? ''),
        'u_pincode' => trim($post['pincode'] ?? ''),
        'u_family'  => trim($post['family'] ?? ''),
        'staff_id'  => trim($post['staff_id'] ?? ''),
    ];
    $errors = [];
    if ($data['u_card'] === '') $errors['card_no'] = 'Student ID / Card No is required.';
    if (strlen($data['u_card']) > 12) $errors['card_no'] = 'Card No must be 12 characters or less.';
    if (strlen($data['u_f_name']) < 2) $errors['user_first_name'] = 'First name is required.';
    if ($data['u_email'] === '' || !filter_var($data['u_email'], FILTER_VALIDATE_EMAIL)) $errors['user_email'] = 'Enter a valid email address.';
    if ($data['u_phone'] === '' || !preg_match('/^[0-9+\-\s]{7,15}$/', $data['u_phone'])) $errors['user_phone'] = 'Enter a valid phone number.';
    if ($data['u_aadhar'] !== '' && !preg_match('/^[0-9]{12}$/', $data['u_aadhar'])) $errors['user_aadhar'] = 'Aadhar number must be exactly 12 digits.';
    if (!in_array($data['u_gender'], ['Male', 'Female', 'Other'], true)) $errors['user_gender'] = 'Please select a gender.';
    if ($data['u_birthday'] !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['u_birthday'])) $errors['user_dob'] = 'Date of birth must be a valid date.';
    if ($data['u_pincode'] !== '' && !preg_match('/^[0-9]{6}$/', $data['u_pincode'])) $errors['pincode'] = 'Pincode must be 6 digits.';
    return [$data, $errors];
}
