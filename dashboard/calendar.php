<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

$resourceFilter = $_GET['resource'] ?? "";

/* Query bookings */
$query = "SELECT b.*, r.name
          FROM bookings b
          JOIN resources r
          ON b.resource_id = r.id";

if ($resourceFilter != "") {
    $query .= " WHERE b.resource_id = " . intval($resourceFilter);
}

$query .= " ORDER BY booking_date ASC, start_time ASC";

$result = mysqli_query($con, $query);

/* Resource list */
$res = mysqli_query($con, "SELECT * FROM resources");
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking Calendar</title>
<link rel="stylesheet" href="calendar.css">
</head>

<body>

<div class="container">

<h2>Booking Calendar</h2>

<form method="GET" action="calendar.php" class="filter-form">

<select name="resource">
<option value="">All Resources</option>

<?php while ($r = mysqli_fetch_assoc($res)) { ?>
<option value="<?php echo $r['id']; ?>"
<?php if ($resourceFilter == $r['id']) echo "selected"; ?>>
<?php echo $r['name']; ?>
</option>
<?php } ?>

</select>

<button type="submit">Filter</button>

</form>

<table>
<tr>
    <th>Date</th>
    <th>Resource</th>
    <th>Time</th>
    <th>Status</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?php echo $row['booking_date']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['start_time']." - ".$row['end_time']; ?></td>

<td class="status <?php echo $row['status']; ?>">
<?php echo ucfirst($row['status']); ?>
</td>

</tr>
<?php } ?>

</table>

<br>
<a class="back-btn" href="dashboard.php">← Back to Dashboard</a>

</div>

</body>
</html>
