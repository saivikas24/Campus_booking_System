<?php
session_start();
include("../config/db.php");
require "../config/mail.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    echo "Access denied.";
    exit();
}

$booking_id = $_GET['id'];

/* ---------- Approve Booking ---------- */
mysqli_query($con,
    "UPDATE bookings
     SET status='approved'
     WHERE id='$booking_id'"
);

/* ---------- Get Booking + User ---------- */
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

/* ---------- Send Email ---------- */
$subject = "Booking Approved";

$message = "
Hello {$data['full_name']},<br><br>
Your booking has been <b>approved</b>.<br><br>

<b>Resource:</b> {$data['name']}<br>
<b>Date:</b> {$data['booking_date']}<br>
<b>Time:</b> {$data['start_time']} - {$data['end_time']}<br><br>

Thank you,<br>
Campus Booking System
";

sendMail($data['email'], $subject, $message);

/* ---------- Redirect ---------- */
header("Location: dashboard.php");
exit();
?>
