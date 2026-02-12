<?php
session_start();

/* Clear session */
session_unset();
session_destroy();

/* Redirect to home page */
header("Location: /campus_booking/index.html");
exit();
?>
