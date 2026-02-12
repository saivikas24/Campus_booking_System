<?php
session_start();
include("../config/db.php");
require "../config/mail.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$resource_id = $_POST['resource_id'];
$date = $_POST['booking_date'];
$start = $_POST['start_time'];
$end = $_POST['end_time'];
$purpose = $_POST['purpose'] ?? '';

$current_date = date("Y-m-d");
$current_time = date("H:i:s");

if ($date < $current_date) exit();
if ($date == $current_date && strtotime($start) < strtotime($current_time)) exit();

/* Conflict Check */
$conflict = mysqli_query($con,
    "SELECT id FROM bookings
     WHERE resource_id='$resource_id'
     AND booking_date='$date'
     AND status IN ('pending','approved')
     AND (
            ('$start' BETWEEN start_time AND end_time)
         OR ('$end' BETWEEN start_time AND end_time)
         OR (start_time BETWEEN '$start' AND '$end')
         )"
);

if (mysqli_num_rows($conflict) > 0) exit();

/* Insert */
mysqli_query($con,
    "INSERT INTO bookings
     (user_id, resource_id, booking_date, start_time, end_time, purpose, status)
     VALUES
     ('$user_id','$resource_id','$date','$start','$end','$purpose','pending')"
);

/* Email */
$userQuery = mysqli_query($con,
    "SELECT email, full_name FROM users WHERE id='$user_id'"
);
$user = mysqli_fetch_assoc($userQuery);

sendMail(
    $user['email'],
    "Booking Submitted",
    "Your booking request was submitted."
);

header("Location: booking_success.php");
exit();
?>
