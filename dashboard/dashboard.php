<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>

<div class="overlay"></div>

<div class="dashboard-container">

    <h1>Welcome <?php echo strtoupper($user_name); ?></h1>

    <p class="success-msg">Login successful</p>

    <div class="dashboard-actions">
        <a href="resources.php">Book a ClassRoom/Sportcourt </a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="calendar.php">Booking Calendar</a>
        <a href="../auth/logout.php" class="logout">Logout</a>
    </div>

</div>

</body>
</html>
