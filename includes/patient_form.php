<?php
/**
 * Shared form used by create.php and update.php
 * Expects: $p (patient data), $errors, $doctors, $isEdit, $buttonText
 */
$bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
?>
<?php if (!empty($errors)): ?>
    <div class="alert error">
        <strong>Please fix the following:</strong>
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" class="card form-grid" novalidate>
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
    <?php endif; ?>

    <div class="field">
        <label for="patient_no">Patient No. *</label>
        <input type="text" id="patient_no" name="patient_no" maxlength="20"
               value="<?= e($p['patient_no']) ?>" <?= $isEdit ? 'readonly' : 'required' ?>>
    </div>

    <div class="field">
        <label for="full_name">Full Name *</label>
        <input type="text" id="full_name" name="full_name" maxlength="100"
               value="<?= e($p['full_name']) ?>" required>
    </div>

    <div class="field">
        <label for="gender">Gender *</label>
        <select id="gender" name="gender" required>
            <option value="">-- Select --</option>
            <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                <option value="<?= $g ?>" <?= $p['gender'] === $g ? 'selected' : '' ?>><?= $g ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <label for="date_of_birth">Date of Birth *</label>
        <input type="date" id="date_of_birth" name="date_of_birth"
               max="<?= date('Y-m-d') ?>" value="<?= e($p['date_of_birth']) ?>" required>
    </div>

    <div class="field">
        <label for="phone">Phone *</label>
        <input type="text" id="phone" name="phone" maxlength="15"
               placeholder="e.g. 0701234567" value="<?= e($p['phone']) ?>" required>
    </div>

    <div class="field">
        <label for="address">Address</label>
        <input type="text" id="address" name="address" maxlength="150"
               value="<?= e($p['address']) ?>">
    </div>

    <div class="field">
        <label for="blood_group">Blood Group</label>
        <select id="blood_group" name="blood_group">
            <option value="">-- Unknown --</option>
            <?php foreach ($bloodGroups as $bg): ?>
                <option value="<?= $bg ?>" <?= $p['blood_group'] === $bg ? 'selected' : '' ?>><?= $bg ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <label for="allergies">Allergies</label>
        <input type="text" id="allergies" name="allergies" maxlength="150"
               placeholder="e.g. Penicillin, None" value="<?= e($p['allergies']) ?>">
    </div>

    <div class="field full">
        <label for="diagnosis">Diagnosis / Reason for Visit</label>
        <textarea id="diagnosis" name="diagnosis" rows="3" maxlength="255"><?= e($p['diagnosis']) ?></textarea>
    </div>

    <div class="field full">
        <label for="doctor_id">Assigned Doctor</label>
        <select id="doctor_id" name="doctor_id">
            <option value="">-- Not assigned --</option>
            <?php foreach ($doctors as $d): ?>
                <option value="<?= (int)$d['id'] ?>" <?= (string)$p['doctor_id'] === (string)$d['id'] ? 'selected' : '' ?>>
                    <?= e($d['full_name']) ?> (<?= e($d['specialization']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="actions full">
        <button type="submit" class="btn primary"><?= e($buttonText) ?></button>
        <a href="index.php" class="btn">Cancel</a>
    </div>
</form>
