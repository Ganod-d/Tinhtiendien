<?php
require 'db.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $terms = isset($_POST['terms']) ? 1 : 0;

    // Validate username
    if (empty($username)) {
        $errors[] = 'Username không được để trống.';
    } elseif (!preg_match('/^[a-zA-Z0-9]{8,20}$/', $username)) {
        $errors[] = 'Username phải chứa 8-20 ký tự, bao gồm chữ cái và số.';
    }

    // Validate password
    if (empty($password)) {
        $errors[] = 'Password không được để trống.';
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,50}$/', $password)) {
        $errors[] = 'Password phải chứa 8-50 ký tự, bao gồm ít nhất 1 chữ in hoa, 1 chữ thường, 1 chữ số, và 1 ký tự đặc biệt.';
    }

    // Validate email
    if (empty($email)) {
        $errors[] = 'Email không được để trống.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ.';
    }

    // Validate phone number
    if (empty($phone)) {
        $errors[] = 'Phone number không được để trống.';
    } elseif (!preg_match('/^\d{10,12}$/', $phone)) {
        $errors[] = 'Phone number phải chứa 10-12 ký tự số.';
    }

    // Validate terms
    if (!$terms) {
        $errors[] = 'Bạn phải đồng ý với điều khoản và điều kiện.';
    }

    // If no errors, proceed to save the user
    if (empty($errors)) {
        $stmt = $pdo->prepare('INSERT INTO users (username, password, first_name, last_name, email, phone) VALUES (?, ?, ?, ?, ?, ?)');
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt->execute([$username, $hashed_password, $first_name, $last_name, $email, $phone]);
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Register</h2>
    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
        
        <label>Password</label>
        <input type="password" name="password" required>
        
        <label>First Name</label>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
        
        <label>Last Name</label>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
        
        <label>Email Address</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
        
        <label>Phone Number</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required>
        
        <label>
            <input type="checkbox" name="terms" <?php echo isset($_POST['terms']) ? 'checked' : ''; ?> required>
            I agree to the terms and conditions
        </label>
        
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
