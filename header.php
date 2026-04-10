<?php
$shop = $_SESSION['shop_name'] ?? 'Shop';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header class="header">
    <div class="header-content">
        <h1>🏪 Kirana Manager</h1>
        <h2><?php echo htmlspecialchars($shop); ?></h2>
        <a href="logout.php">Logout</a>
    </div>
</header>