<?php
require 'config.php';
require 'functions.php';
checkLogin();

$user_id = $_SESSION['user_id'];

// Add request
if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add_req') {
    $product = sanitize($_POST['product'] ?? '');
    
    $sql = "INSERT INTO customer_requests (user_id, product_name, req_count, req_date) 
            VALUES (?, ?, 1, NOW())
            ON DUPLICATE KEY UPDATE req_count = req_count + 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $product);
    $stmt->execute();
    $stmt->close();
    header("Location: requests.php?msg=Added");
    exit;
}

// Mark as stocked
if($_GET['mark'] ?? false) {
    $id = intval($_GET['mark']);
    $sql = "UPDATE customer_requests SET is_stocked = 1 WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: requests.php");
    exit;
}

$reqs = getUnmetRequests($user_id, $conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests - Kirana Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="main">
        <?php include 'sidebar.php'; ?>
        
        <div class="content">
            <h1>🤔 Customer Requests</h1>
            
            <div class="section">
                <h2>➕ Log Request</h2>
                <p style="color:#666; margin-bottom:15px;">When customer asks for something you don't have, note it here</p>
                <form method="POST" class="form">
                    <div class="form-row">
                        <input type="text" name="product" placeholder="What did customer ask for?" required>
                        <button type="submit">📝 Log</button>
                    </div>
                    <input type="hidden" name="action" value="add_req">
                </form>
            </div>
            
            <div class="section">
                <h2>⏳ Unfulfilled Requests (Demand Signals)</h2>
                <table>
                    <tr>
                        <th>Product Requested</th>
                        <th>Times Requested</th>
                        <th>First Asked</th>
                        <th>Action</th>
                    </tr>
                    <?php while($req = $reqs->fetch_assoc()): 
                        $demand = $req['req_count'] >= 5 ? '🔥 High' : ($req['req_count'] >= 3 ? '🟡 Med' : '🟢 Low');
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($req['product_name']); ?></strong></td>
                        <td><span class="badge"><?php echo $req['req_count']; ?> times</span> <?php echo $demand; ?></td>
                        <td><?php echo fmt_date($req['req_date']); ?></td>
                        <td><a href="?mark=<?php echo $req['id']; ?>" class="btn-small">✅ Stocked</a></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>