<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.html");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    echo "Access denied.";
    exit();
}

/* ---------- Auto Complete Past Bookings ---------- */
mysqli_query($con,
    "UPDATE bookings
     SET status='completed'
     WHERE status='approved'
     AND (
            booking_date < CURDATE()
         OR (booking_date = CURDATE()
             AND end_time < CURTIME())
         )"
);

/* ---------- Status Filter ---------- */
$statusFilter = $_GET['status'] ?? 'all';

$whereClause = "";

if ($statusFilter != 'all') {
    $whereClause = "WHERE b.status='$statusFilter'";
}

/* ---------- Analytics ---------- */
$totalBookings = mysqli_fetch_assoc(
    mysqli_query($con, "SELECT COUNT(*) as total FROM bookings")
)['total'];

$todayBookings = mysqli_fetch_assoc(
    mysqli_query($con,
        "SELECT COUNT(*) as total
         FROM bookings
         WHERE booking_date = CURDATE()")
)['total'];

$popularResource = mysqli_fetch_assoc(
    mysqli_query($con,
        "SELECT r.name, COUNT(*) as count
         FROM bookings b
         JOIN resources r ON b.resource_id = r.id
         GROUP BY b.resource_id
         ORDER BY count DESC
         LIMIT 1")
);

/* ---------- Booking Table ---------- */
$result = mysqli_query($con,
    "SELECT b.id, u.full_name, r.name,
            b.booking_date, b.start_time,
            b.end_time, b.status, b.purpose
     FROM bookings b
     JOIN users u ON b.user_id = u.id
     JOIN resources r ON b.resource_id = r.id
     $whereClause
     ORDER BY b.booking_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link rel="stylesheet" href="admin.css">
</head>

<body>

<div class="container">

<h2>Admin Dashboard</h2>

<!-- Analytics -->
<div class="stats">

<div class="card">
<h3>Total Bookings</h3>
<p><?php echo $totalBookings; ?></p>
</div>

<div class="card">
<h3>Bookings Today</h3>
<p><?php echo $todayBookings; ?></p>
</div>

<div class="card">
<h3>Most Used Resource</h3>
<p><?php echo $popularResource['name'] ?? "No bookings"; ?></p>
</div>

</div>

<!-- Status Filter -->
<div style="margin-bottom:15px;">
<b>Filter:</b>
<a href="?status=all">All</a> |
<a href="?status=pending">Pending</a> |
<a href="?status=approved">Approved</a> |
<a href="?status=rejected">Rejected</a> |
<a href="?status=completed">Completed</a> |
<a href="?status=cancelled">Cancelled</a>
</div>

<h3>Booking Approval Panel</h3>

<table>
<tr>
<th>User</th>
<th>Resource</th>
<th>Date</th>
<th>Time</th>
<th>Purpose</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?php echo $row['full_name']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['booking_date']; ?></td>
<td><?php echo $row['start_time']." - ".$row['end_time']; ?></td>

<td><?php echo $row['purpose']; ?></td>

<td class="status <?php echo $row['status']; ?>">
<?php echo ucfirst($row['status']); ?>
</td>

<td>
<a class="approve"
   href="approve.php?id=<?php echo $row['id']; ?>">
   Approve
</a>

<a class="reject"
   href="reject.php?id=<?php echo $row['id']; ?>">
   Reject
</a>
</td>
</tr>
<?php } ?>

</table>

<br>
<a class="logout" href="../auth/logout.php">Logout</a>

</div>

</body>
</html>
