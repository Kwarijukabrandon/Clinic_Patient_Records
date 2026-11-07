<?php
/**
 * delete.php  -  DELETE (Remove a patient)
 * Accepts POST only (safer than a link) and uses a prepared statement.
 */
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    setFlash('error', "Invalid request.");
    header("Location: index.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    setFlash('error', "Invalid patient selected.");
} else {
    try {
        $stmt = $conn->prepare("DELETE FROM patients WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            setFlash('success', "Patient record deleted successfully.");
        } else {
            setFlash('error', "Patient not found or already deleted.");
        }
        $stmt->close();

    } catch (mysqli_sql_exception $ex) {
        setFlash('error', "Error deleting record: " . $ex->getMessage());
    }
}

$conn->close();
header("Location: index.php");
exit;
