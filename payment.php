<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $method = $_POST['payment_method'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $kwh = $_POST['kwh'];
    $cost = $_POST['cost'];
    $email = $_SESSION['user_email'];

    // Gửi email xác nhận
    require 'send_email.php';
    sendPaymentEmail($email, $month, $year, $kwh, $cost, $method);

    echo 'Payment successful! A confirmation email has been sent.';
    exit;
}

// Lấy thông tin số tiền điện của user
$stmt = $pdo->prepare('SELECT * FROM usages WHERE user_id = ? ORDER BY year DESC, month DESC');
$stmt->execute([$user_id]);
$usages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Payment</h2>
    <form method="POST">
        <label for="usage">Select Usage</label>
        <select name="usage" id="usage" required>
            <?php foreach ($usages as $usage): ?>
                <option value="<?php echo htmlspecialchars(json_encode($usage)); ?>">
                    <?php echo htmlspecialchars($usage['month'] . ' ' . $usage['year'] . ' - $' . $usage['cost']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="payment_method">Payment Method</label>
        <select name="payment_method" id="payment_method" required>
            <option value="bank_transfer">Chuyển khoản ngân hàng</option>
            <option value="e_wallet">Ví điện tử</option>
        </select>
        <br>
        <button type="submit">Pay</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedUsage = JSON.parse(document.getElementById('usage').value);
            const kwhInput = document.createElement('input');
            kwhInput.type = 'hidden';
            kwhInput.name = 'kwh';
            kwhInput.value = selectedUsage.kwh;
            e.target.appendChild(kwhInput);

            const costInput = document.createElement('input');
            costInput.type = 'hidden';
            costInput.name = 'cost';
            costInput.value = selectedUsage.cost;
            e.target.appendChild(costInput);

            const monthInput = document.createElement('input');
            monthInput.type = 'hidden';
            monthInput.name = 'month';
            monthInput.value = selectedUsage.month;
            e.target.appendChild(monthInput);

            const yearInput = document.createElement('input');
            yearInput.type = 'hidden';
            yearInput.name = 'year';
            yearInput.value = selectedUsage.year;
            e.target.appendChild(yearInput);
        });
    </script>
</body>
</html>
