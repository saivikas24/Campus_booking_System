<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

$resource_id = $_GET['resource_id'];

$resource = mysqli_fetch_assoc(
    mysqli_query($con,
        "SELECT * FROM resources WHERE id='$resource_id'")
);

$bookings = mysqli_query($con,
    "SELECT booking_date, start_time, end_time
     FROM bookings
     WHERE resource_id='$resource_id'
     ORDER BY booking_date");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Resource</title>
    <link rel="stylesheet" href="book.css">
</head>

<body>

<div class="container">

<h2><?php echo $resource['name']; ?></h2>
<p><b>Location:</b> <?php echo $resource['location']; ?></p>
<p><b>Capacity:</b> <?php echo $resource['capacity']; ?></p>

<hr>

<h3>Already Booked Slots</h3>

<table>
<tr>
    <th>Date</th>
    <th>Start</th>
    <th>End</th>
</tr>

<?php while ($b = mysqli_fetch_assoc($bookings)) { ?>
<tr>
    <td><?php echo $b['booking_date']; ?></td>
    <td><?php echo $b['start_time']; ?></td>
    <td><?php echo $b['end_time']; ?></td>
</tr>
<?php } ?>

</table>

<hr>

<h3>Book Slot</h3>

<form action="save_booking.php" method="POST">

<input type="hidden" name="resource_id"
       value="<?php echo $resource_id; ?>">

<label>Date</label>
<input type="date" name="booking_date" required>

<label>Start Time</label>
<input type="time" name="start_time" required>

<label>End Time</label>
<input type="time" name="end_time" required>

<!-- Purpose field -->
<label>Purpose of Booking (required for halls/classrooms)</label>
<textarea name="purpose"
          placeholder="Enter purpose (seminar, meeting, workshop etc.)"
          rows="3"></textarea>

<button type="submit">Confirm Booking</button>

</form>

<br>
<a class="back" href="resources.php">Back to Resources</a>

</div>

</body>
</html>
