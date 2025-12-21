<?php
session_start();
include "../db.php";

if (!isset($_SESSION['regno'])) {
    header("Location: signin.php");
    exit();
}

if (isset($_POST['submit'])) {

    $regno = $_SESSION['regno'];
    $course = $_POST['course'];
    $college = $_POST['college'];
    $semester = $_POST['semester'];
    $college_contact = $_POST['college_contact'];
    $last_exam = $_POST['last_exam'];
    $total_marks = $_POST['total_marks'];
    $obtained_marks = $_POST['obtained_marks'];
    $percentage = $_POST['percentage'];
    $id_type = $_POST['id_type'];
    $id_number = $_POST['id_number'];
    $jan = $_POST['jan'];
    $feb = $_POST['feb'];
    $mar = $_POST['mar'];
    $apr = $_POST['apr'];
    $may = $_POST['may'];

    $sql = "INSERT INTO promotion
    ( course, college_name, semester, college_contact, last_exam,
     total_marks, obtained_marks, percentage, id_type, id_number,
     prayer_jan, prayer_feb, prayer_mar, prayer_apr, prayer_may)
    VALUES
    ('$course','$college','$semester','$college_contact','$last_exam',
     '$total_marks','$obtained_marks','$percentage','$id_type','$id_number',
     '$jan','$feb','$mar','$apr','$may')";

    if ($conn->query($sql)) {
        header("Location: promotion.php");
        exit();
    } else {
        echo "Error";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Promotion</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h2>Student Promotion</h2><hr>
<h3>Upload Documents</h3>

<form method="post" enctype="multipart/form-data">

<label>Course Name*<br></label>
<select name="course" required>
    <option value="">Select Course</option>
    <option value="10th">10th Higher Secondary</option>
    <option value="11th Science">11th Science</option>
    <option value="12th Science">12th Science</option>
    <option value="11th Commerce">11th Commerce</option>
    <option value="12th Commerce">12th Commerce</option>
    <option value="11th Arts">11th Arts</option>
    <option value="12th Arts">12th Arts</option>
</select><br><br>

<label>College/School Name*<br></label>
<input type="text" name="college" required><br><br>

<label>Semester/Standard*<br></label>
<select name="semester" required>
    <option value="">--Select Semester--</option>
    <option value="10th">10th</option>
    <option value="11th">11th</option>
    <option value="12th">12th</option>
    <option value="1st Semester">1st Semester</option>
    <option value="2nd Semester">2nd Semester</option>
    <option value="3rd Semester">3rd Semester</option>
    <option value="4th Semester">4th Semester</option>
    <option value="5th Semester">5th Semester</option>
    <option value="6th Semester">6th Semester</option>
</select><br><br>

<label>College/School Contact No*<br></label>
<input type="tel" name="college_contact" pattern="[0-9]{10}" required><br><br>

<label>Last Exam Name*<br></label>
<input type="text" name="last_exam" required><br><br>

<label>Last Exam Total Mark*<br></label>
<input type="number" name="total_marks" required><br><br>

<label>Last Exam Obtain Marks*<br></label>
<input type="number" name="obtained_marks" required><br><br>

<label>Last Exam Percentage*<br></label>
<input type="number" step="0.01" name="percentage" required><br><br>

<label>ID proof Type*</label><br>
<select name="id_type" required>
    <option value="">--Select Type--</option>
    <option value="Pan Card">Pan Card</option>
    <option value="Aadhar Card">Aadhar Card</option>
    <option value="Driving License">Driving License</option>
    <option value="Passport">Passport</option>
    <option value="Ration Card">Ration Card</option>
</select><br><br>

<label>ID proof No*<br></label>
<input type="text" name="id_number" required><br><br>

<label>Prayer Jan 2025 Present (days)*<br></label>
<input type="number" name="jan" required><br><br>

<label>Prayer Feb 2025 Present (days)*<br></label>
<input type="number" name="feb" required><br><br>

<label>Prayer March 2025 Present (days)*<br></label>
<input type="number" name="mar" required><br><br>

<label>Prayer April 2025 Present (days)*<br></label>
<input type="number" name="apr" required><br><br>

<label>Prayer May 2025 Present (days)<br></label>
<input type="number" name="may"><br><br>

<input type="submit" name="submit" value="Submit">
</form>

</body>
</html>
