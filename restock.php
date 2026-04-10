<?php
require 'config.php';
require 'functions.php';
checkLogin();

$user_id = $_SESSION['user_id'];
$items = getRestockList($user_id, $conn);
$items_data = $items->fetch_all(MYSQLI_ASSOC);

$total_cost = 0;
foreach($items_data as $item) {
    $needed = $item['max_stock'] - $item['qty_stock'];
    $total_cost += $needed * $item['price_cost'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restock - Kirana Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="main">
        <?php include 'sidebar.php'; ?>
        
        <div class="content">
            <h1>📋 Smart Restock List</h1>
            
            <div class="section">
                <h2>📦 Today's Restock Recommendations</h2>
                <?php if(count($items_data) > 0): ?>
                
                <div class="restock-summary">
                    <p><strong>Items to buy:</strong> <?php echo count($items_data); ?></p>
                    <p><strong>Total Investment:</strong> <span class="highlight"><?php echo fmt_currency($total_cost); ?></span></p>
                </div>
                
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Current Stock</th>
                        <th>Min Stock</th>
                        <th>Max Stock</th>
                        <th>Qty to Buy</th>
                        <th>Cost/Unit</th>
                        <th>Total Cost</th>
                    </tr>
                    <?php foreach($items_data as $item):
                        $to_buy = $item['max_stock'] - $item['qty_stock'];
                        $item_cost = $to_buy * $item['price_cost'];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo $item['qty_stock']; ?></td>
                        <td><?php echo $item['min_stock']; ?></td>
                        <td><?php echo $item['max_stock']; ?></td>
                        <td class="highlight"><strong><?php echo $to_buy; ?></strong></td>
                        <td><?php echo fmt_currency($item['price_cost']); ?></td>
                        <td><strong><?php echo fmt_currency($item_cost); ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                
                <?php else: ?>
                <p class="empty">🎉 All products have good stock! No restock needed.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>