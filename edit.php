<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_login(); // FIX: the old edit.php had NO login check - anyone could edit any student

$id = (int)($_GET['id'] ?? 0);
$stmt = mysqli_prepare($con, 'SELECT * FROM student_data WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
if (!$student) {
    set_flash('error', 'Student not found.');
    redirect('index.php');
}

// Map DB columns back to the form's field names
$old = [
    'card_no' => $student['u_card'], 'user_first_name' => $student['u_f_name'], 'user_last_name' => $student['u_l_name'],
    'user_father' => $student['u_father'], 'user_mother' => $student['u_mother'], 'user_aadhar' => $student['u_aadhar'],
    'user_dob' => $student['u_birthday'], 'user_gender' => $student['u_gender'], 'user_email' => $student['u_email'],
    'user_phone' => $student['u_phone'], 'state' => $student['u_state'], 'dist' => $student['u_dist'],
    'village' => $student['u_village'], 'police_station' => $student['u_police'], 'pincode' => $student['u_pincode'],
    'family' => $student['u_family'], 'staff_id' => $student['staff_id'],
];
$currentImage = $student['image'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    [$data, $errors] = validate_student($_POST);
    // FIX: keep the old photo when no new photo is chosen (the old code wiped it with an empty value)
    [$image, $imageError] = handle_image_upload('image', $student['image']);
    if ($imageError) $errors['image'] = $imageError;
    if (!$errors) {
        $sql = 'UPDATE student_data SET u_card=?, u_f_name=?, u_l_name=?, u_father=?, u_aadhar=?, u_birthday=?, u_gender=?, u_email=?, u_phone=?, u_state=?, u_dist=?, u_village=?, u_police=?, u_pincode=?, u_mother=?, u_family=?, staff_id=?, image=? WHERE id=?';
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssssssssssssssssi',
            $data['u_card'], $data['u_f_name'], $data['u_l_name'], $data['u_father'], $data['u_aadhar'],
            $data['u_birthday'], $data['u_gender'], $data['u_email'], $data['u_phone'], $data['u_state'],
            $data['u_dist'], $data['u_village'], $data['u_police'], $data['u_pincode'], $data['u_mother'],
            $data['u_family'], $data['staff_id'], $image, $id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            set_flash('success', 'Student updated successfully.');
            redirect('view.php?id=' . $id);
        }
        mysqli_stmt_close($stmt);
        $errors['form'] = 'Data not updated. Please try again.';
    }
    $old = $_POST;
    $currentImage = $image ?? $currentImage;
}

$pageTitle = 'Edit Student';
require_once __DIR__ . '/header.php';
?>
<div class="page-head"><h1>Edit Student</h1><p><?= e(student_full_name($student)) ?> &bull; <?= e($student['u_card']) ?></p></div>
<?php if (isset($errors['form'])): ?><div class="alert alert-error"><?= e($errors['form']) ?></div><?php endif; ?>
<?php $formTitle = 'Edit Student Details'; require __DIR__ . '/includes/student-form.php'; ?>
<?php require_once __DIR__ . '/footer.php'; ?>
