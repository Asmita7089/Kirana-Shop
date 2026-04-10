<?php
require 'config.php';
require 'functions.php';
checkLogin();

$user_id = $_SESSION['user_id'];
$today = getTodaySales($user_id, $conn);
$low_stock = getLowStock($user_id, $conn);
$running_out = getRunningOut($user_id, $conn);
$total_products = countProducts($user_id, $conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kirana Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="main">
        <?php include 'sidebar.php'; ?>
        
        <div class="content">
            <h1>📊 Dashboard</h1>
            
            <div class="stats-row">
                <div class="stat">
                    <h3>Today's Revenue</h3>
                    <div class="big-num"><?php echo fmt_currency($today['revenue'] ?? 0); ?></div>
                </div>
                <div class="stat">
                    <h3>Items Sold</h3>
                    <div class="big-num"><?php echo $today['items'] ?? 0; ?></div>
                </div>
                <div class="stat">
                    <h3>Total Products</h3>
                    <div class="big-num"><?php echo $total_products; ?></div>
                </div>
                <div class="stat alert">
                    <h3>🔴 Low Stock</h3>
                    <div class="big-num"><?php echo $low_stock->num_rows; ?></div>
                </div>
            </div>
            
            <!-- Low Stock Alert -->
            <?php if($low_stock->num_rows > 0): ?>
            <div class="section">
                <h2>🔴 Low Stock - Restock Now!</h2>
                <div class="items-grid">
                    <?php while($item = $low_stock->fetch_assoc()): ?>
                    <div class="item-box red">
                        <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                        <p>Stock: <span class="red-text"><?php echo $item['qty_stock']; ?></span></p>
                        <p>Price: <?php echo fmt_currency($item['price_sell']); ?></p>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Running Out Soon -->
            <?php $running_out_data = $running_out->fetch_all(MYSQLI_ASSOC); ?>
            <?php if(count($running_out_data) > 0): ?>
            <div class="section">
                <h2>⏰ Running Out Soon (Sales Speed Based)</h2>
                <div class="items-grid">
                    <?php foreach($running_out_data as $item): ?>
                    <div class="item-box yellow">
                        <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                        <p>Stock: <?php echo $item['qty_stock']; ?></p>
                        <p>Finishes in: <span class="orange-text"><?php echo round($item['days_left']); ?> days</span></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>