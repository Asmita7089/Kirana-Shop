<?php
require 'config.php';
require 'functions.php';
if(isLoggedIn()) redirect('dashboard.php');

$error = $success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shop_name = sanitize($_POST['shop_name'] ?? '');
    $owner_name = sanitize($_POST['owner_name'] ?? '');
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    
    if(strlen($password) < 6) {
        $error = '❌ Password must be 6+ characters';
    } elseif($password !== $confirm) {
        $error = '❌ Passwords do not match';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (shop_name, owner_name, username, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $shop_name, $owner_name, $username, $hash);
        
        if($stmt->execute()) {
            $success = '✅ Registration successful! <a href="index.php">Login here</a>';
        } else {
            $error = '❌ Username already exists';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kirana Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <div class="auth-box" style="max-width: 500px;">
        <h1>📝 Create Account</h1>
        
        <?php if($error): ?>
            <div class="alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert-success"><?php echo $success; ?></div>
        <?php else: ?>
            <form method="POST">
                <input type="text" name="shop_name" placeholder="Shop Name" required>
                <input type="text" name="owner_name" placeholder="Owner Name" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password (6+ chars)" required>
                <input type="password" name="confirm" placeholder="Confirm Password" required>
                <button type="submit">✅ Register</button>
            </form>
        <?php endif; ?>
        
        <p style="text-align:center; margin-top:20px;">
            Have account? <a href="index.php">Login here</a>
        </p>
    </div>
</body>
</html>