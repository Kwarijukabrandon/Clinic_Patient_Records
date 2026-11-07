<?php
/**
 * config.php
 * Database connection using OOP-MySQLi.
 * Every page includes this file, so the connection is written only once.
 */

// Make MySQLi throw exceptions so errors can be handled with try/catch
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = "localhost";
$username   = "root";
$password   = "";            // XAMPP default is an empty password
$dbname     = "clinic_db";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("<h3 style='font-family:Arial;color:#b91c1c'>Connection failed: "
        . htmlspecialchars($e->getMessage())
        . "<br><small>Check that MySQL is running and that you imported database.sql</small></h3>");
}

session_start();

require_once __DIR__ . '/includes/functions.php';
