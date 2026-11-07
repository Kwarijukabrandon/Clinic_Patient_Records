<?php
/**
 * functions.php
 * Small helper functions used by several pages.
 */

/** Escape output to prevent XSS */
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/** Store a one-time message (shown after redirect) */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/** Read and clear the one-time message */
function getFlash(): ?array
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/** Get all doctors for the dropdown list */
function getDoctors(mysqli $conn): array
{
    $result = $conn->query("SELECT id, full_name, specialization FROM doctors ORDER BY full_name");
    return $result->fetch_all(MYSQLI_ASSOC);
}

/** Suggest the next patient number, e.g. PT-0006 */
function nextPatientNo(mysqli $conn): string
{
    $result = $conn->query("SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM patients");
    $row = $result->fetch_assoc();
    return 'PT-' . str_pad($row['next_id'], 4, '0', STR_PAD_LEFT);
}

/** Calculate age from date of birth */
function calculateAge(string $dob): int
{
    return (new DateTime($dob))->diff(new DateTime('today'))->y;
}

/**
 * Validate and clean the patient form input.
 * Returns [cleanData, errorsArray]
 */
function validatePatient(array $in, bool $requirePatientNo = true): array
{
    $fields = ['patient_no', 'full_name', 'gender', 'date_of_birth', 'phone',
               'address', 'blood_group', 'allergies', 'diagnosis', 'doctor_id'];

    $p = [];
    foreach ($fields as $f) {
        $p[$f] = trim($in[$f] ?? '');
    }

    $errors = [];

    if ($requirePatientNo && $p['patient_no'] === '') {
        $errors[] = "Patient number is required.";
    }
    if ($p['full_name'] === '') {
        $errors[] = "Full name is required.";
    } elseif (strlen($p['full_name']) < 3) {
        $errors[] = "Full name must be at least 3 characters.";
    }
    if (!in_array($p['gender'], ['Male', 'Female', 'Other'], true)) {
        $errors[] = "Please select a valid gender.";
    }
    if ($p['date_of_birth'] === '') {
        $errors[] = "Date of birth is required.";
    } else {
        $d = DateTime::createFromFormat('Y-m-d', $p['date_of_birth']);
        if (!$d || $d->format('Y-m-d') !== $p['date_of_birth']) {
            $errors[] = "Date of birth is not a valid date.";
        } elseif ($d > new DateTime('today')) {
            $errors[] = "Date of birth cannot be in the future.";
        }
    }
    if (!preg_match('/^[0-9+\s-]{9,15}$/', $p['phone'])) {
        $errors[] = "Enter a valid phone number (9 to 15 digits).";
    }

    return [$p, $errors];
}
