<?php
// get the pin and value parameters from URL
$pin_number = $_REQUEST["pin"];
$status     = $_REQUEST["value"];

$mysqli = new mysqli("localhost:8889", "x", "x", "PiControllerInfo");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/* close connection */
$mysqli->close();

echo $status;
?>