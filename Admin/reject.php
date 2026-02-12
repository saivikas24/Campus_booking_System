<?php
session_start();
include("../config/db.php");
require "../config/mail.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "Access denied";
    exit();
}

$booking_id = $_GET['id'];

/* ---------- Handle Form Submit ---------- */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $reason = $_POST['reason'];

    /* Update status */
    mysqli_query($con,
        "UPDATE bookings
         SET status='rejected'
         WHERE id='$booking_id'"
    );

    /* Get booking + user details */
    $query = mysqli_query($con,
        "SELECT u.email, u.full_name,
                r.name,
                b.booking_date,
                b.start_time,
                b.end_time
         FROM bookings b
         JOIN users u ON b.user_id = u.id
         JOIN resources r ON b.resource_id = r.id
         WHERE b.id='$booking_id'"
    );

    $data = mysqli_fetch_assoc($query);

    /* Send Email */
    $subject = "Booking Rejected";

    $message = "
Hello {$data['full_name']},<br><br>
Your booking request has been <b>rejected</b>.<br><br>

<b>Resource:</b> {$data['name']}<br>
<b>Date:</b> {$data['booking_date']}<br>
<b>Time:</b> {$data['start_time']} - {$data['end_time']}<br><br>

<b>Reason:</b> {$reason}<br><br>

Please contact admin if needed.<br><br>

Campus Booking System
";

    sendMail($data['email'], $subject, $message);

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Reject Booking</title>
<style>
body {
    font-family: Arial;
    padding: 50px;
}

textarea {
    width: 100%;
    height: 100px;
}

button {
    padding: 10px;
    margin-top: 10px;
}
</style>
</head>

<body>

<h2>Reject Booking</h2>

<form method="POST">

<label>Reason for rejection:</label><br>
<textarea name="reason" required></textarea><br>

<button type="submit">Submit Rejection</button>

</form>

</body>
</html>
