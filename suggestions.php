<?php
require 'config.php';
require 'functions.php';
checkLogin();

$user_id = $_SESSION['user_id'];
$combos = getComboSuggestions($user_id, $conn);
$slow = getSlowMoving($user_id, $conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggestions - Kirana Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="main">
        <?php include 'sidebar.php'; ?>
        
        <div class="content">
            <h1>💡 Smart Suggestions</h1>
            
            <!-- Dead Stock -->
            <div class="section">
                <h2>💀 Dead Stock - Items Not Selling</h2>
                <div class="items-grid">
                    <?php while($item = $slow->fetch_assoc()): ?>
                    <div class="item-box">
                        <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                        <p>Stock: <span class="red-text"><?php echo $item['qty_stock']; ?> units</span></p>
                        <p>Value: <?php echo fmt_currency($item['qty_stock'] * $item['price_cost']); ?></p>
                        <p style="font-size:12px; margin-top:10px;">
                            💡 Try: Discount 20% | Bundle Deal | Clearance Sale
                        </p>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            
            <!-- Auto Combos -->
            <div class="section">
                <h2>🤝 Auto Combo Suggestions</h2>
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Suggested Combo</th>
                        <th>Benefit</th>
                    </tr>
                    <?php while($item = $combos->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($item['product_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($item['suggestion']); ?></td>
                        <td>Increase basket 10-15%</td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>