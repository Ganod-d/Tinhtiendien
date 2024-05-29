<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}

// Ví dụ về nội dung của trang thông báo số điện
?>
<!DOCTYPE html>
<html>
<head>
    <title>Electricity Notifications</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Electricity Notifications</h2>
    <p>Here you will find the notifications about your electricity usage.</p>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
