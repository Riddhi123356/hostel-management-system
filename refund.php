<?php
session_start();
include "db.php";
include "includes/functions.php";
require_student_login();

$regno = $_SESSION['regno'];
$error = "";

if (isset($_POST['submit_refund'])) {
    csrf_verify();

    $request_type = trim($_POST['request_type'] ?? '');
    $amount = $_POST['amount'] ?? '';
    $bank_name = trim($_POST['bank_name'] ?? '');
    $account_no = trim($_POST['account_no'] ?? '');
    $ifsc = trim($_POST['ifsc'] ?? '');

    if ($request_type === '' || $amount === '' || $bank_name === '' || $account_no === '' || $ifsc === '') {
        $error = "Please fill in all required fields.";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO refund_requests (registration_no, request_type, amount, bank_name, account_no, ifsc)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssdsss", $regno, $request_type, $amount, $bank_name, $account_no, $ifsc);

        if ($stmt->execute()) {
            header("Location: refund.php");
            exit();
        } else {
            $error = "Something went wrong while submitting your request.";
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM refund_requests WHERE registration_no = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $regno);
$stmt->execute();
$result = $stmt->get_result();

$activePage = 'refund.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Refund Requests</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "sidebar.php"; ?>

    <div class="main">
        <h2>Refund Requests</h2>
        <p class="page-subtitle">Submit a refund request and track its approval stage across departments.</p>

        <div class="panel">
            <?php if ($error) { ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php } ?>

            <form method="post">
                <?= csrf_field() ?>

                <label>Request Type*</label>
                <select name="request_type" required>
                    <option value="">Select</option>
                    <option value="Hostel Fee Refund">Hostel Fee Refund</option>
                    <option value="Mess Fee Refund">Mess Fee Refund</option>
                    <option value="Security Deposit Refund">Security Deposit Refund</option>
                </select>

                <label>Amount (&#8377;)*</label>
                <input type="number" step="0.01" name="amount" required>

                <label>Bank Name*</label>
                <input type="text" name="bank_name" required>

                <label>Account Number*</label>
                <input type="text" name="account_no" required>

                <label>IFSC Code*</label>
                <input type="text" name="ifsc" required>

                <button type="submit" name="submit_refund" class="btn-primary">Submit Request</button>
            </form>
        </div>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>Reg No.</th>
                    <th>Request Type</th>
                    <th>Amount</th>
                    <th>Bank</th>
                    <th>Account No</th>
                    <th>IFSC</th>
                    <th>Director</th>
                    <th>Rector</th>
                    <th>Librarian</th>
                    <th>Accountant</th>
                    <th>Created At</th>
                </tr>

                <?php if ($result->num_rows > 0) {
                    while ($r = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= e($r['registration_no']) ?></td>
                    <td><?= e($r['request_type']) ?></td>
                    <td>&#8377; <?= e(number_format((float)$r['amount'], 2)) ?></td>
                    <td><?= e($r['bank_name']) ?></td>
                    <td><?= e($r['account_no']) ?></td>
                    <td><?= e($r['ifsc']) ?></td>
                    <td><?= status_badge($r['director_status']) ?></td>
                    <td><?= status_badge($r['rector_status']) ?></td>
                    <td><?= status_badge($r['librarian_status']) ?></td>
                    <td><?= status_badge($r['accountant_status']) ?></td>
                    <td><?= format_date($r['created_at']) ?></td>
                </tr>
                <?php }
                } else { ?>
                <tr>
                    <td colspan="11" class="empty-row">No refund requests found.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>
