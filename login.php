<?php
require 'db.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Validate username
    if (empty($username)) {
        $errors[] = 'Username không được để trống.';
    } elseif (strlen($username) < 3 || strlen($username) > 30) {
        $errors[] = 'Độ dài Username phải nằm trong khoảng 3 đến 30 ký tự.';
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = 'Password không được để trống.';
    } elseif (strlen($password) < 6 || strlen($password) > 10) {
        $errors[] = 'Độ dài Password phải nằm trong khoảng 6 đến 10 ký tự.';
    }

    // If no errors, proceed to check credentials
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard');
            exit;
        } else {
            $errors[] = 'Username hoặc Password đã nhập sai.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Login</h2>
    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>
