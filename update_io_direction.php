<?php
// get the pin and value parameters from URL
$id = $_REQUEST["id"];
$direction  = $_REQUEST["value"];

require_once 'login.php';
$connection = new mysqli($hostname, $username, $password, $database);
if ($connection->connect_error) die($connection->connect_error);

if($direction == "output")
{
  $query = "UPDATE GPIO SET isOutput=1 WHERE id=$id";
}
elseif ($direction == "input") {
  $query = "UPDATE GPIO SET isOutput=0 WHERE id=$id";
}

$result = $connection->query($query);
if (!$result) die($connection->connect_error);

echo $direction;
?>
