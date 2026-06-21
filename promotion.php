<?php
session_start();
include "db.php";
include "includes/functions.php";
require_student_login();

$regno = $_SESSION['regno'];
$error = "";

if (isset($_POST['submit'])) {
    csrf_verify();

    $course = $_POST['course'] ?? '';
    $college = trim($_POST['college'] ?? '');
    $semester = $_POST['semester'] ?? '';
    $college_contact = trim($_POST['college_contact'] ?? '');
    $last_exam = trim($_POST['last_exam'] ?? '');
    $total_marks = $_POST['total_marks'] ?? '';
    $obtained_marks = $_POST['obtained_marks'] ?? '';
    $percentage = $_POST['percentage'] ?? '';
    $id_type = $_POST['id_type'] ?? '';
    $id_number = trim($_POST['id_number'] ?? '');
    $jan = $_POST['jan'] ?? null;
    $feb = $_POST['feb'] ?? null;
    $mar = $_POST['mar'] ?? null;
    $apr = $_POST['apr'] ?? null;
    $may = $_POST['may'] ?? null;

    if ($course === '' || $college === '' || $semester === '' || $college_contact === '' ||
        $last_exam === '' || $total_marks === '' || $obtained_marks === '' || $percentage === '' ||
        $id_type === '' || $id_number === '') {
        $error = "Please fill in all required fields.";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO promotion
                (regno, course, college_name, semester, college_contact, last_exam,
                 total_marks, obtained_marks, percentage, id_type, id_number,
                 prayer_jan, prayer_feb, prayer_mar, prayer_apr, prayer_may)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");
        $stmt->bind_param(
            "ssssssiidssiiiii",
            $regno, $course, $college, $semester, $college_contact, $last_exam,
            $total_marks, $obtained_marks, $percentage, $id_type, $id_number,
            $jan, $feb, $mar, $apr, $may
        );

        if ($stmt->execute()) {
            header("Location: promotion.php");
            exit();
        } else {
            $error = "Something went wrong while submitting your form.";
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM promotion WHERE regno = ? ORDER BY id DESC");
$stmt->bind_param("s", $regno);
$stmt->execute();
$history = $stmt->get_result();

$activePage = 'promotion.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Promotion</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app-shell">
    <?php include "sidebar.php"; ?>

    <div class="main">
        <h2>Student Promotion</h2>
        <p class="page-subtitle">Submit your academic progress documents for the new term.</p>

        <div class="panel wide">
            <?php if ($error) { ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php } ?>

            <form method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <label>Course Name*</label>
                <select name="course" required>
                    <option value="">Select Course</option>
                    <option value="10th">10th Higher Secondary</option>
                    <option value="11th Science">11th Science</option>
                    <option value="12th Science">12th Science</option>
                    <option value="11th Commerce">11th Commerce</option>
                    <option value="12th Commerce">12th Commerce</option>
                    <option value="11th Arts">11th Arts</option>
                    <option value="12th Arts">12th Arts</option>
                </select>

                <label>College / School Name*</label>
                <input type="text" name="college" required>

                <label>Semester / Standard*</label>
                <select name="semester" required>
                    <option value="">Select Semester</option>
                    <option value="10th">10th</option>
                    <option value="11th">11th</option>
                    <option value="12th">12th</option>
                    <option value="1st Semester">1st Semester</option>
                    <option value="2nd Semester">2nd Semester</option>
                    <option value="3rd Semester">3rd Semester</option>
                    <option value="4th Semester">4th Semester</option>
                    <option value="5th Semester">5th Semester</option>
                    <option value="6th Semester">6th Semester</option>
                </select>

                <label>College / School Contact No*</label>
                <input type="tel" name="college_contact" pattern="[0-9]{10}" required>

                <label>Last Exam Name*</label>
                <input type="text" name="last_exam" required>

                <label>Last Exam Total Marks*</label>
                <input type="number" name="total_marks" id="total_marks" required>

                <label>Last Exam Obtained Marks*</label>
                <input type="number" name="obtained_marks" id="obtained_marks" required>

                <label>Last Exam Percentage*</label>
                <input type="number" step="0.01" name="percentage" id="percentage" required>

                <label>ID Proof Type*</label>
                <select name="id_type" required>
                    <option value="">Select Type</option>
                    <option value="Pan Card">Pan Card</option>
                    <option value="Aadhar Card">Aadhar Card</option>
                    <option value="Driving License">Driving License</option>
                    <option value="Passport">Passport</option>
                    <option value="Ration Card">Ration Card</option>
                </select>

                <label>ID Proof No*</label>
                <input type="text" name="id_number" required>

                <label>Prayer Jan — Present (days)*</label>
                <input type="number" name="jan" required>

                <label>Prayer Feb — Present (days)*</label>
                <input type="number" name="feb" required>

                <label>Prayer Mar — Present (days)*</label>
                <input type="number" name="mar" required>

                <label>Prayer Apr — Present (days)*</label>
                <input type="number" name="apr" required>

                <label>Prayer May — Present (days)</label>
                <input type="number" name="may">

                <button type="submit" name="submit" class="btn-primary">Submit</button>
            </form>
        </div>

        <h3>Your Previous Submissions</h3>
        <div class="table-wrap">
            <table>
                <tr>
                    <th>Course</th>
                    <th>College</th>
                    <th>Semester</th>
                    <th>Last Exam</th>
                    <th>Percentage</th>
                    <th>Submitted</th>
                </tr>
                <?php if ($history->num_rows > 0) {
                    while ($r = $history->fetch_assoc()) { ?>
                <tr>
                    <td><?= e($r['course']) ?></td>
                    <td><?= e($r['college_name']) ?></td>
                    <td><?= e($r['semester']) ?></td>
                    <td><?= e($r['last_exam']) ?></td>
                    <td><?= e($r['percentage']) ?>%</td>
                    <td><?= format_date($r['created_at']) ?></td>
                </tr>
                <?php }
                } else { ?>
                <tr>
                    <td colspan="6" class="empty-row">No submissions yet.</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

<script src="js/javascript.js"></script>
</body>
</html>
