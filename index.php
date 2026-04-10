<?php
require 'config.php';
require 'functions.php'; 

if(isLoggedIn()) redirect('dashboard.php');

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $sql = "SELECT id, shop_name, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    
    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['shop_name'] = $user['shop_name'];
        redirect('dashboard.php');
    } else {
        $error = '❌ Invalid credentials';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kirana Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <div class="auth-box">
        <h1>🏪 Kirana Shop Manager</h1>
        <p>Smart inventory for small shops</p>
        
        <?php if($error): ?>
            <div class="alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">🔓 Login</button>
        </form>
        
        <p style="text-align:center; margin-top:20px;">
            New user? <a href="register.php">Register here</a>
        </p>
    </div>
</body>
</html>