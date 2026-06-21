<?php
session_start();
include "db.php";
include "includes/functions.php";
require_student_login();

$regno = $_SESSION['regno'];
$error = "";

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

// Make sure this fee record actually belongs to the logged-in student.
$stmt = $conn->prepare("SELECT * FROM fees WHERE id = ? AND regno = ?");
$stmt->bind_param("is", $id, $regno);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    header("Location: fees.php");
    exit();
}

if ($row['fees_status'] === 'paid') {
    header("Location: fees.php");
    exit();
}

$paymentDone = false;

if (isset($_POST['pay'])) {
    csrf_verify();

    $cardNumber = trim($_POST['card_number'] ?? '');
    $cardName = trim($_POST['card_name'] ?? '');
    $expiry = trim($_POST['expiry'] ?? '');
    $cvv = trim($_POST['cvv'] ?? '');

    if ($cardNumber === '' || $cardName === '' || $expiry === '' || $cvv === '') {
        $error = "Please fill in all payment details.";
    } else {
        // SIMULATED PAYMENT — no real payment gateway is involved.
        // This just marks the fee record as paid for demo purposes.
        $update = $conn->prepare("UPDATE fees SET fees_status = 'paid' WHERE id = ? AND regno = ?");
        $update->bind_param("is", $id, $regno);
        $update->execute();

        $paymentDone = true;
    }
}

$activePage = 'fees.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pay Hostel Fees</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "sidebar.php"; ?>

    <div class="main">
        <h2>Pay Hostel Fees</h2>
        <p class="page-subtitle">
            This is a simulated payment for demo purposes — no real card details are processed or stored.
        </p>

        <?php if ($paymentDone) { ?>
            <div class="panel">
                <div class="alert alert-success">
                    Payment successful! &#8377;<?= e(number_format((float)$row['fees_amount'], 2)) ?> has been marked as paid.
                </div>
                <a href="fees.php" class="btn btn-primary">Back to Fees</a>
            </div>
        <?php } else { ?>
            <div class="panel">
                <p style="margin-top:0;">
                    <strong>Amount Due:</strong>
                    &#8377; <?= e(number_format((float)$row['fees_amount'], 2)) ?>
                </p>

                <?php if ($error) { ?>
                    <div class="alert alert-error"><?= e($error) ?></div>
                <?php } ?>

                <form method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">

                    <label>Cardholder Name</label>
                    <input type="text" name="card_name" placeholder="Name on card" required>

                    <label>Card Number</label>
                    <input type="text" name="card_number" placeholder="4242 4242 4242 4242" maxlength="19" required>

                    <div style="display:flex; gap:12px;">
                        <div style="flex:1;">
                            <label>Expiry</label>
                            <input type="text" name="expiry" placeholder="MM/YY" maxlength="5" required>
                        </div>
                        <div style="flex:1;">
                            <label>CVV</label>
                            <input type="text" name="cvv" placeholder="123" maxlength="3" required>
                        </div>
                    </div>

                    <button type="submit" name="pay" class="btn-amber">
                        Pay &#8377; <?= e(number_format((float)$row['fees_amount'], 2)) ?>
                    </button>
                    <a href="fees.php" class="btn btn-outline">Cancel</a>
                </form>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>