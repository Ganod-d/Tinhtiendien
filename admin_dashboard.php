<?php
require 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

$stmt = $pdo->prepare('SELECT usages.*, users.username FROM usages JOIN users ON usages.user_id = users.id ORDER BY year DESC, month DESC');
$stmt->execute();
$usages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Admin Dashboard</h2>
    <h3>Electricity Usage Management</h3>
    <table border="1">
        <thead>
            <tr>
                <th>User</th>
                <th>Month</th>
                <th>Year</th>
                <th>kWh Used</th>
                <th>Cost ($)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usages as $usage): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usage['username']); ?></td>
                    <td><?php echo htmlspecialchars($usage['month']); ?></td>
                    <td><?php echo htmlspecialchars($usage['year']); ?></td>
                    <td><?php echo htmlspecialchars($usage['kwh']); ?></td>
                    <td><?php echo htmlspecialchars($usage['cost']); ?></td>
                    <td>
                        <a href="edit_usage.php?id=<?php echo $usage['id']; ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="admin_logout.php">Logout</a>
</body>
</html>
