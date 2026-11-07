<?php
/**
 * index.php  -  READ (Retrieve and display patients)
 * Includes a search box (prepared statement with LIKE).
 */
require_once 'config.php';

$pageTitle = "Patient Records";
$search    = trim($_GET['q'] ?? '');
$patients  = [];
$error     = '';
$total     = 0;

try {
    // Simple (non-prepared) query for the total count
    $countResult = $conn->query("SELECT COUNT(*) AS total FROM patients");
    $total = $countResult->fetch_assoc()['total'];

    $baseSql = "SELECT p.*, d.full_name AS doctor_name
                FROM patients p
                LEFT JOIN doctors d ON p.doctor_id = d.id";

    if ($search !== '') {
        $stmt = $conn->prepare(
            $baseSql . " WHERE p.full_name LIKE ? OR p.patient_no LIKE ? OR p.phone LIKE ?
                         ORDER BY p.id DESC"
        );
        $like = "%" . $search . "%";
        $stmt->bind_param("sss", $like, $like, $like);
    } else {
        $stmt = $conn->prepare($baseSql . " ORDER BY p.id DESC");
    }

    $stmt->execute();
    $result   = $stmt->get_result();
    $patients = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

} catch (mysqli_sql_exception $ex) {
    $error = "Could not retrieve records: " . $ex->getMessage();
}

require 'includes/header.php';
?>

<div class="page-head">
    <div>
        <h1>Patient Records</h1>
        <p class="muted">Total registered patients: <strong><?= (int)$total ?></strong></p>
    </div>
    <a href="create.php" class="btn primary">+ Register Patient</a>
</div>

<form method="GET" class="search">
    <input type="text" name="q" placeholder="Search by name, patient no. or phone"
           value="<?= e($search) ?>">
    <button type="submit" class="btn primary">Search</button>
    <?php if ($search !== ''): ?>
        <a href="index.php" class="btn">Clear</a>
    <?php endif; ?>
</form>

<?php if ($error): ?>
    <div class="alert error"><?= e($error) ?></div>
<?php endif; ?>

<?php if (count($patients) > 0): ?>
<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>Patient No.</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Phone</th>
            <th>Blood</th>
            <th>Allergies</th>
            <th>Diagnosis</th>
            <th>Doctor</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($patients as $row): ?>
        <tr>
            <td><strong><?= e($row['patient_no']) ?></strong></td>
            <td><?= e($row['full_name']) ?></td>
            <td><?= e($row['gender']) ?></td>
            <td><?= calculateAge($row['date_of_birth']) ?></td>
            <td><?= e($row['phone']) ?></td>
            <td><?= e($row['blood_group'] ?: '-') ?></td>
            <td><?= e($row['allergies'] ?: '-') ?></td>
            <td><?= e($row['diagnosis'] ?: '-') ?></td>
            <td><?= e($row['doctor_name'] ?: 'Not assigned') ?></td>
            <td class="row-actions">
                <a href="update.php?id=<?= (int)$row['id'] ?>" class="btn small">Edit</a>
                <form method="POST" action="delete.php"
                      onsubmit="return confirm('Delete <?= e(addslashes($row['full_name'])) ?>? This cannot be undone.');">
                    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                    <button type="submit" class="btn small danger">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php elseif (!$error): ?>
    <div class="card empty">0 results found.</div>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
