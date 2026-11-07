<?php
/**
 * update.php  -  UPDATE (Modify an existing patient)
 * Loads the record, pre-fills the form, then saves with a prepared statement.
 */
require_once 'config.php';

$pageTitle  = "Edit Patient";
$isEdit     = true;
$buttonText = "Update Patient";
$errors     = [];
$doctors    = getDoctors($conn);

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    setFlash('error', "Invalid patient selected.");
    header("Location: index.php");
    exit;
}

try {
    // Fetch the existing record (prepared SELECT)
    $stmt = $conn->prepare("SELECT * FROM patients WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();
    $stmt->close();
} catch (mysqli_sql_exception $ex) {
    setFlash('error', "Error loading patient: " . $ex->getMessage());
    header("Location: index.php");
    exit;
}

if (!$existing) {
    setFlash('error', "Patient not found.");
    header("Location: index.php");
    exit;
}

// Pre-fill the form with the existing data
$p = $existing;
$p['doctor_id'] = $existing['doctor_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$clean, $errors] = validatePatient($_POST, false);
    $p = array_merge($existing, $clean);
    $p['patient_no'] = $existing['patient_no'];   // patient number cannot be changed

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare(
                "UPDATE patients
                 SET full_name = ?, gender = ?, date_of_birth = ?, phone = ?, address = ?,
                     blood_group = ?, allergies = ?, diagnosis = ?, doctor_id = ?
                 WHERE id = ?"
            );

            $doctorId = ($p['doctor_id'] === '') ? null : (int)$p['doctor_id'];

            $stmt->bind_param(
                "ssssssssii",
                $p['full_name'], $p['gender'], $p['date_of_birth'], $p['phone'],
                $p['address'], $p['blood_group'], $p['allergies'], $p['diagnosis'],
                $doctorId, $id
            );

            $stmt->execute();
            $changed = $stmt->affected_rows;
            $stmt->close();

            setFlash('success', $changed > 0
                ? "Patient '{$p['full_name']}' updated successfully."
                : "No changes were made to '{$p['full_name']}'.");
            header("Location: index.php");
            exit;

        } catch (mysqli_sql_exception $ex) {
            $errors[] = "Database error: " . $ex->getMessage();
        }
    }
}

require 'includes/header.php';
?>
<h1>Edit Patient</h1>
<?php require 'includes/patient_form.php'; ?>
<?php require 'includes/footer.php'; ?>
