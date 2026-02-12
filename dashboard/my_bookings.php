<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

$result = mysqli_query($con,
    "SELECT b.id, r.name, b.booking_date,
            b.start_time, b.end_time, b.status
     FROM bookings b
     JOIN resources r ON b.resource_id = r.id
     WHERE b.user_id = '$user_id'
     ORDER BY b.booking_date DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <link rel="stylesheet" href="my_bookings.css">
</head>

<body>

<div class="container">

<h2>My Bookings</h2>

<?php if(mysqli_num_rows($result) == 0){ ?>
    <p>No bookings yet.</p>
<?php } else { ?>

<table>
<tr>
    <th>Resource</th>
    <th>Date</th>
    <th>Time</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['booking_date']; ?></td>
    <td><?php echo $row['start_time']." - ".$row['end_time']; ?></td>

    <td class="status <?php echo $row['status']; ?>">
        <?php echo ucfirst($row['status']); ?>
    </td>

    <td>
        <?php if($row['status']=="pending"){ ?>
        <a class="cancel-btn"
           href="cancel_booking.php?id=<?php echo $row['id']; ?>">
           Cancel
        </a>
        <?php } else { echo "-"; } ?>
    </td>
</tr>
<?php } ?>

</table>

<?php } ?>

<br>
<a class="back" href="dashboard.php">← Back to Dashboard</a>

</div>

</body>
</html>
