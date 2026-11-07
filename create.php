<?php
/**
 * create.php  -  CREATE (Insert a new patient)
 * Uses a PREPARED STATEMENT to insert safely.
 */
require_once 'config.php';

$pageTitle = "Register Patient";
$isEdit    = false;
$buttonText = "Save Patient";
$errors    = [];
$doctors   = getDoctors($conn);

// Default (empty) form values
$p = [
    'patient_no' => nextPatientNo($conn), 'full_name' => '', 'gender' => '',
    'date_of_birth' => '', 'phone' => '', 'address' => '', 'blood_group' => '',
    'allergies' => '', 'diagnosis' => '', 'doctor_id' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$p, $errors] = validatePatient($_POST);

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO patients
                 (patient_no, full_name, gender, date_of_birth, phone, address,
                  blood_group, allergies, diagnosis, doctor_id)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $doctorId = ($p['doctor_id'] === '') ? null : (int)$p['doctor_id'];

            $stmt->bind_param(
                "sssssssssi",
                $p['patient_no'], $p['full_name'], $p['gender'], $p['date_of_birth'],
                $p['phone'], $p['address'], $p['blood_group'], $p['allergies'],
                $p['diagnosis'], $doctorId
            );

            $stmt->execute();
            $stmt->close();

            setFlash('success', "Patient '{$p['full_name']}' registered successfully.");
            header("Location: index.php");
            exit;

        } catch (mysqli_sql_exception $ex) {
            if ($ex->getCode() == 1062) {
                $errors[] = "Patient number '{$p['patient_no']}' already exists.";
            } else {
                $errors[] = "Database error: " . $ex->getMessage();
            }
        }
    }
}

require 'includes/header.php';
?>
<h1>Register New Patient</h1>
<?php require 'includes/patient_form.php'; ?>
<?php require 'includes/footer.php'; ?>
