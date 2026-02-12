<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

$result = mysqli_query($con, "SELECT * FROM resources");
?>

<!DOCTYPE html>
<html>
<head>
<title>Resources</title>
<link rel="stylesheet" href="resources.css">
</head>

<body>

<h2>Available Resources</h2>

<div class="resource-grid">

<?php while ($row = mysqli_fetch_assoc($result)) { 

/* Check if busy today */
$rid = $row['id'];

$busyCheck = mysqli_query($con,
    "SELECT id FROM bookings
     WHERE resource_id='$rid'
     AND booking_date = CURDATE()
     AND status IN ('pending','approved')"
);

$isBusy = mysqli_num_rows($busyCheck) > 0;
?>

<div class="card">

<img src="../assets/images/resources/<?php echo $row['image_url']; ?>">

<h3><?php echo $row['name']; ?></h3>

<p><b>Location:</b> <?php echo $row['location']; ?></p>
<p><b>Capacity:</b> <?php echo $row['capacity']; ?></p>

<?php if($isBusy){ ?>
<p class="busy">🔴 Busy Today</p>
<?php } else { ?>
<p class="free">🟢 Available Today</p>
<?php } ?>

<a href="book.php?resource_id=<?php echo $row['id']; ?>">
Book Now
</a>

</div>

<?php } ?>

</div>

<br>
<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>
