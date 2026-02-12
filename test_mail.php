<?php
require 'config/mail.php';

$result = sendMail(
    "a.saivikas18@gmail.com",
    "Test Mail",
    "Mail system working successfully!"
);

if ($result) {
    echo "Email sent successfully!";
} else {
    echo "Email sending failed!";
}
?>
