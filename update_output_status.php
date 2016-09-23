<?php
// get the pin and value parameters from URL
$id = $_REQUEST["id"];
$status = $_REQUEST["value"];

require_once 'login.php';
$connection = new mysqli($hostname, $username, $password, $database);
if ($connection->connect_error) die($connection->connect_error);

if($status == "true")
{
  $query = "UPDATE GPIO SET isHigh=1 WHERE id=$id";
}
elseif ($status == "false") {
  $query = "UPDATE GPIO SET isHigh=0 WHERE id=$id";
}

$result = $connection->query($query);
if (!$result) die($connection->connect_error);

/* close connection */
$connection->close();

echo $status;
?>
