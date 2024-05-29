<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy dữ liệu tiền điện từ cơ sở dữ liệu
$stmt = $pdo->prepare('SELECT * FROM usages WHERE user_id = ? ORDER BY year DESC, month DESC');
$stmt->execute([$user_id]);
$usages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Dashboard</h2>
    <h3>Electricity Usage and Costs</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Month</th>
                <th>Year</th>
                <th>kWh Used</th>
                <th>Cost ($)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usages as $usage): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usage['month']); ?></td>
                    <td><?php echo htmlspecialchars($usage['year']); ?></td>
                    <td><?php echo htmlspecialchars($usage['kwh']); ?></td>
                    <td><?php echo htmlspecialchars($usage['cost']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="logout">Logout</a>
</body>
</html>
