<?php
require 'config.php';
require 'functions.php';
checkLogin();

$user_id = $_SESSION['user_id'];

// Add sale
if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add_sale') {
    $product_id = intval($_POST['product_id'] ?? 0);
    $qty = intval($_POST['qty'] ?? 0);
    
    // Get product price
    $p_sql = "SELECT price_sell, qty_stock FROM products WHERE id = ? AND user_id = ?";
    $p_stmt = $conn->prepare($p_sql);
    $p_stmt->bind_param("ii", $product_id, $user_id);
    $p_stmt->execute();
    $product = $p_stmt->get_result()->fetch_assoc();
    $p_stmt->close();
    
    if($product && $product['qty_stock'] >= $qty) {
        $total = $product['price_sell'] * $qty;
        
        // Insert sale
        $s_sql = "INSERT INTO sales (user_id, product_id, qty_sold, price_unit, total_amount, sale_date) 
                 VALUES (?, ?, ?, ?, ?, NOW())";
        $s_stmt = $conn->prepare($s_sql);
        $s_stmt->bind_param("iiidd", $user_id, $product_id, $qty, $product['price_sell'], $total);
        $s_stmt->execute();
        $s_stmt->close();
        
        // Update stock
        $u_sql = "UPDATE products SET qty_stock = qty_stock - ? WHERE id = ?";
        $u_stmt = $conn->prepare($u_sql);
        $u_stmt->bind_param("ii", $qty, $product_id);
        $u_stmt->execute();
        $u_stmt->close();
        
        header("Location: sales.php?msg=Sale added");
        exit;
    }
}

// Get products
$p_sql = "SELECT id, product_name, price_sell, qty_stock FROM products WHERE user_id = ? AND active = 1 ORDER BY product_name";
$p_stmt = $conn->prepare($p_sql);
$p_stmt->bind_param("i", $user_id);
$p_stmt->execute();
$products = $p_stmt->get_result();

// Get today's sales
$today = date('Y-m-d');
$sales_sql = "SELECT s.*, p.product_name FROM sales s 
              JOIN products p ON s.product_id = p.id
              WHERE s.user_id = ? AND DATE(s.sale_date) = ? 
              ORDER BY s.sale_date DESC";
$sales_stmt = $conn->prepare($sales_sql);
$sales_stmt->bind_param("is", $user_id, $today);
$sales_stmt->execute();
$today_sales = $sales_stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales - Kirana Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="main">
        <?php include 'sidebar.php'; ?>
        
        <div class="content">
            <h1>🧾 Quick Sale</h1>
            
            <div class="section">
                <h2>➕ Record Sale</h2>
                <form method="POST" class="form">
                    <div class="form-row">
                        <select name="product_id" id="prod" required onchange="updatePrice()">
                            <option value="">-- Select Product --</option>
                            <?php 
                            $products->data_seek(0);
                            while($p = $products->fetch_assoc()): 
                            ?>
                            <option value="<?php echo $p['id']; ?>" data-price="<?php echo $p['price_sell']; ?>" data-stock="<?php echo $p['qty_stock']; ?>">
                                <?php echo htmlspecialchars($p['product_name']); ?> - ₹<?php echo $p['price_sell']; ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                        <input type="number" name="qty" id="qty" placeholder="Qty" min="1" required onchange="updatePrice()">
                    </div>
                    <div class="total-box">
                        Total: ₹<span id="total">0.00</span>
                    </div>
                    <input type="hidden" name="action" value="add_sale">
                    <button type="submit">✅ Add Sale</button>
                </form>
            </div>
            
            <div class="section">
                <h2>Today's Sales</h2>
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Time</th>
                    </tr>
                    <?php while($s = $today_sales->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($s['product_name']); ?></td>
                        <td><?php echo $s['qty_sold']; ?></td>
                        <td><?php echo fmt_currency($s['price_unit']); ?></td>
                        <td><strong><?php echo fmt_currency($s['total_amount']); ?></strong></td>
                        <td><?php echo fmt_time($s['sale_date']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
    
    <script>
    function updatePrice() {
        const sel = document.getElementById('prod');
        const price = sel.options[sel.selectedIndex]?.getAttribute('data-price') || 0;
        const qty = document.getElementById('qty').value || 0;
        document.getElementById('total').textContent = (price * qty).toFixed(2);
    }
    </script>
</body>
</html>