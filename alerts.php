<?php
require 'config.php';
require 'functions.php';
checkLogin();

$user_id = $_SESSION['user_id'];
$low_stock = getLowStock($user_id, $conn);
$running_out = getRunningOut($user_id, $conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts - Kirana Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="main">
        <?php include 'sidebar.php'; ?>
        
        <div class="content">
            <h1>🔔 Smart Alerts</h1>
            
            <!-- Low Stock -->
            <div class="section">
                <h2>🔴 Low Stock Items</h2>
                <?php if($low_stock->num_rows > 0): ?>
                <div class="items-grid">
                    <?php while($item = $low_stock->fetch_assoc()): ?>
                    <div class="item-box red">
                        <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                        <p>Only <span class="red-text"><?php echo $item['qty_stock']; ?></span> packets left</p>
                        <p>Min Required: <?php echo $item['min_stock']; ?></p>
                        <p>Price: <?php echo fmt_currency($item['price_sell']); ?></p>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php else: ?>
                <p class="empty">✅ All products in good stock!</p>
                <?php endif; ?>
            </div>
            
            <!-- Running Out Soon -->
            <div class="section">
                <h2>⏰ Running Out Soon</h2>
                <?php $data = $running_out->fetch_all(MYSQLI_ASSOC); ?>
                <?php if(count($data) > 0): ?>
                <div class="items-grid">
                    <?php foreach($data as $item): ?>
                    <div class="item-box yellow">
                        <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                        <p>Stock: <?php echo $item['qty_stock']; ?></p>
                        <p>Finishes in: <span class="orange-text"><?php echo round($item['days_left']); ?> days</span></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="empty">🟢 All products have stable stock</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>