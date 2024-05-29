<?php
require 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $kwh = $_POST['kwh'];
    $cost = $_POST['cost'];

    $stmt = $pdo->prepare('UPDATE usages SET kwh = ?, cost = ? WHERE id = ?');
    $stmt->execute([$kwh, $cost, $id]);

    header('Location: admin_dashboard.php');
    exit;
} else {
    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM usages WHERE id = ?');
    $stmt->execute([$id]);
    $usage = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Usage</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Edit Usage</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($usage['id']); ?>">
        <label>kWh Used</label>
        <input type="number" name="kwh" value="<?php echo htmlspecialchars($usage['kwh']); ?>" required>
        <label>Cost ($)</label>
        <input type="number" step="0.01" name="cost" value="<?php echo htmlspecialchars($usage['cost']); ?>" required>
        <button type="submit">Update</button>
    </form>
</body>
</html>
