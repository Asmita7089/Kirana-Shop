<?php
require 'config.php';
require 'functions.php';
checkLogin();

$user_id = $_SESSION['user_id'];

// Add product
if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add') {
    $name = sanitize($_POST['name'] ?? '');
    $qty = intval($_POST['qty'] ?? 0);
    $cost = floatval($_POST['cost'] ?? 0);
    $sell = floatval($_POST['sell'] ?? 0);
    $min = intval($_POST['min'] ?? 5);
    $max = intval($_POST['max'] ?? 100);
    
    $sql = "INSERT INTO products (user_id, product_name, qty_stock, price_cost, price_sell, min_stock, max_stock) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isidddii", $user_id, $name, $qty, $cost, $sell, $min, $max);
    $stmt->execute();
    $stmt->close();
    header("Location: products.php?msg=Added");
    exit;
}

$sql = "SELECT * FROM products WHERE user_id = ? AND active = 1 ORDER BY product_name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$products = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Kirana Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="main">
        <?php include 'sidebar.php'; ?>
        
        <div class="content">
            <h1>📦 Products</h1>
            
            <div class="section">
                <h2>➕ Add Product</h2>
                <form method="POST" class="form">
                    <div class="form-row">
                        <input type="text" name="name" placeholder="Product Name" required>
                        <input type="number" name="qty" placeholder="Qty" required>
                    </div>
                    <div class="form-row">
                        <input type="number" step="0.01" name="cost" placeholder="Cost Price" required>
                        <input type="number" step="0.01" name="sell" placeholder="Selling Price" required>
                    </div>
                    <div class="form-row">
                        <input type="number" name="min" placeholder="Min Stock" value="5">
                        <input type="number" name="max" placeholder="Max Stock" value="100">
                    </div>
                    <input type="hidden" name="action" value="add">
                    <button type="submit">➕ Add</button>
                </form>
            </div>
            
            <div class="section">
                <h2>Your Products</h2>
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Stock</th>
                        <th>Cost</th>
                        <th>Sell</th>
                        <th>Profit/Unit</th>
                    </tr>
                    <?php while($p = $products->fetch_assoc()): 
                        $profit = $p['price_sell'] - $p['price_cost'];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['product_name']); ?></td>
                        <td class="<?php echo $p['qty_stock'] <= $p['min_stock'] ? 'red-text' : ''; ?>">
                            <?php echo $p['qty_stock']; ?>
                        </td>
                        <td><?php echo fmt_currency($p['price_cost']); ?></td>
                        <td><?php echo fmt_currency($p['price_sell']); ?></td>
                        <td><?php echo fmt_currency($profit); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>