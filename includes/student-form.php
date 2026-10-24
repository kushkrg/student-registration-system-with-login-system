<?php
// Shared Add / Edit student form. Expects: $old (values keyed by form field names), $errors, $formTitle, $currentImage
$v = fn(string $k) => e($old[$k] ?? '');
$err = function (string $k) use ($errors) {
    return isset($errors[$k]) ? '<div class="error-text">' . e($errors[$k]) . '</div>' : '';
};
?>
<div class="card">
  <h2><?= e($formTitle) ?></h2>
  <form method="post" enctype="multipart/form-data" novalidate>
    <?= csrf_field() ?>
    <div class="form-grid">
      <div class="form-section">Student Information</div>
      <div class="form-group"><label>Student ID / Card No *</label><input name="card_no" maxlength="12" value="<?= $v('card_no') ?>" placeholder="e.g. STD123456789"><?= $err('card_no') ?></div>
      <div class="form-group"><label>Staff ID</label><input name="staff_id" value="<?= $v('staff_id') ?>" placeholder="Staff ID who registered"></div>
      <div class="form-group"><label>First Name *</label><input name="user_first_name" value="<?= $v('user_first_name') ?>" placeholder="First name"><?= $err('user_first_name') ?></div>
      <div class="form-group"><label>Last Name</label><input name="user_last_name" value="<?= $v('user_last_name') ?>" placeholder="Last name"></div>
      <div class="form-group"><label>Gender *</label><select name="user_gender"><option value="">-- Select --</option><?php foreach (['Male','Female','Other'] as $g): ?><option value="<?= $g ?>" <?= ($old['user_gender'] ?? '') === $g ? 'selected' : '' ?>><?= $g ?></option><?php endforeach; ?></select><?= $err('user_gender') ?></div>
      <div class="form-group"><label>Date of Birth</label><input name="user_dob" type="date" value="<?= $v('user_dob') ?>"><?= $err('user_dob') ?></div>
      <div class="form-group"><label>Aadhar No (12 digits)</label><input name="user_aadhar" maxlength="12" value="<?= $v('user_aadhar') ?>" placeholder="XXXX XXXX XXXX (digits only)"><?= $err('user_aadhar') ?></div>
      <div class="form-group"><label>Student Photo (JPG/PNG/WEBP, max 2MB)</label><input name="image" type="file" accept=".jpg,.jpeg,.png,.webp"><?= $err('image') ?>
      <?php if (!empty($currentImage)): ?><small style="color:var(--muted)">Current photo is saved - choose a file only to replace it.</small><?php endif; ?></div>

      <div class="form-section">Family Information</div>
      <div class="form-group"><label>Father Name</label><input name="user_father" value="<?= $v('user_father') ?>"></div>
      <div class="form-group"><label>Mother Name</label><input name="user_mother" value="<?= $v('user_mother') ?>"></div>
      <div class="form-group"><label>Family Members</label><input name="family" value="<?= $v('family') ?>" placeholder="e.g. 5"></div>

      <div class="form-section">Contact Information</div>
      <div class="form-group"><label>Email *</label><input name="user_email" type="email" value="<?= $v('user_email') ?>" placeholder="student@example.com"><?= $err('user_email') ?></div>
      <div class="form-group"><label>Mobile No *</label><input name="user_phone" value="<?= $v('user_phone') ?>" placeholder="10-digit mobile number"><?= $err('user_phone') ?></div>

      <div class="form-section">Address</div>
      <div class="form-group"><label>Village / Town</label><input name="village" value="<?= $v('village') ?>"></div>
      <div class="form-group"><label>Police Station</label><input name="police_station" value="<?= $v('police_station') ?>"></div>
      <div class="form-group"><label>District</label><input name="dist" value="<?= $v('dist') ?>"></div>
      <div class="form-group"><label>State</label><input name="state" value="<?= $v('state') ?>"></div>
      <div class="form-group"><label>Pincode</label><input name="pincode" maxlength="6" value="<?= $v('pincode') ?>"><?= $err('pincode') ?></div>
    </div>
    <div style="margin-top:22px;display:flex;gap:10px;flex-wrap:wrap">
      <button class="btn" type="submit" name="submit">Save Student</button>
      <a class="btn btn-light" href="index.php">Cancel</a>
    </div>
  </form>
</div>
