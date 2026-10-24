<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_login(); // FIX: the old add.php had NO login check - anyone could add students

$errors = [];
$old = [];
$currentImage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    [$data, $errors] = validate_student($_POST);
    [$image, $imageError] = handle_image_upload('image');
    if ($imageError) $errors['image'] = $imageError;
    if (!$errors) {
        // FIX: prepared statement - the old version pasted raw $_POST values into the SQL string (SQL injection)
        $sql = 'INSERT INTO student_data (u_card, u_f_name, u_l_name, u_father, u_aadhar, u_birthday, u_gender, u_email, u_phone, u_state, u_dist, u_village, u_police, u_pincode, u_mother, u_family, staff_id, image, uploaded)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())';
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssssssssssssssss',
            $data['u_card'], $data['u_f_name'], $data['u_l_name'], $data['u_father'], $data['u_aadhar'],
            $data['u_birthday'], $data['u_gender'], $data['u_email'], $data['u_phone'], $data['u_state'],
            $data['u_dist'], $data['u_village'], $data['u_police'], $data['u_pincode'], $data['u_mother'],
            $data['u_family'], $data['staff_id'], $image);
        if (mysqli_stmt_execute($stmt)) {
            $newId = mysqli_insert_id($con);
            mysqli_stmt_close($stmt);
            set_flash('success', 'Student added successfully.');
            redirect('view.php?id=' . $newId);
        }
        mysqli_stmt_close($stmt);
        $errors['form'] = 'Data not inserted. Please try again.';
    }
    $old = $_POST;
}

$pageTitle = 'Add Student';
require_once __DIR__ . '/header.php';
?>
<div class="page-head"><h1>Add New Student</h1><p>Student registration form</p></div>
<?php if (isset($errors['form'])): ?><div class="alert alert-error"><?= e($errors['form']) ?></div><?php endif; ?>
<?php $formTitle = 'Register a Student'; require __DIR__ . '/includes/student-form.php'; ?>
<?php require_once __DIR__ . '/footer.php'; ?>
