<?php
session_start();
include("../config/db.php");
require "../config/mail.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = $_GET['id'];

/* ---------- Cancel Booking ---------- */
mysqli_query($con,
    "UPDATE bookings
     SET status='cancelled'
     WHERE id='$booking_id'
     AND user_id='$user_id'"
);

/* ---------- Get Booking Info ---------- */
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
$subject = "Booking Cancelled";

$message = "
Hello {$data['full_name']},<br><br>
Your booking has been successfully <b>cancelled</b>.<br><br>

<b>Resource:</b> {$data['name']}<br>
<b>Date:</b> {$data['booking_date']}<br>
<b>Time:</b> {$data['start_time']} - {$data['end_time']}<br><br>

If this was accidental, you may book again anytime.<br><br>

Thank you,<br>
Campus Booking System
";

sendMail($data['email'], $subject, $message);

/* ---------- Redirect ---------- */
header("Location: my_bookings.php");
exit();
?>
