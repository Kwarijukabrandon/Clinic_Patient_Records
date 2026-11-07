<?php
// Expects $pageTitle to be set before including
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Clinic System') ?> | Clinic Patient Records</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <div class="brand">&#10010; City Health Clinic</div>
    <nav>
        <a href="index.php">Patient Records</a>
        <a href="create.php">+ Register Patient</a>
    </nav>
</header>
<main class="container">
<?php if ($flash): ?>
    <div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
<?php endif; ?>
