<?php
require 'config.php';
require 'functions.php';
checkLogin();

$user_id = $_SESSION['user_id'];
$top_sellers = getTopSellers($user_id, $conn);
$slow_items = getSlowMoving($user_id, $conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Kirana Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="main">
        <?php include 'sidebar.php'; ?>
        
        <div class="content">
            <h1>📊 Analytics</h1>
            
            <!-- Best Sellers -->
            <div class="section">
                <h2>🔥 Fast-Moving (Best Sellers)</h2>
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Units Sold</th>
                        <th>Revenue</th>
                    </tr>
                    <?php while($item = $top_sellers->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($item['product_name']); ?></strong></td>
                        <td><?php echo $item['total_sold']; ?> units</td>
                        <td><?php echo fmt_currency($item['revenue']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            
            <!-- Slow Moving -->
            <div class="section">
                <h2>🐢 Slow-Moving (Apply Discount)</h2>
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Stock</th>
                        <th>Sales (30d)</th>
                        <th>Value Blocked</th>
                        <th>Action</th>
                    </tr>
                    <?php while($item = $slow_items->fetch_assoc()): 
                        $value = $item['qty_stock'] * $item['price_cost'];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo $item['qty_stock']; ?></td>
                        <td><?php echo $item['sales_count']; ?> units</td>
                        <td><?php echo fmt_currency($value); ?></td>
                        <td><span class="badge">💡 Discount 20%</span></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>