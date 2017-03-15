<?php
    require_once 'login.php';
    $connection = new mysqli($hostname, $username, $password, $database);
    if ($connection->connect_error) die($connection->connect_error);

    $query = "SELECT * FROM GPIO";
    $result = $connection->query($query);
    if (!$result) die($connection->connect_error);

    $num_rows = $result->num_rows;
    $gpio_info = array();

    for($j = 0; $j < $num_rows; ++$j)
    {
        $result->data_seek($j);
        $gpio_info[] = $result->fetch_array(MYSQLI_ASSOC);
    }

    $result->close();
    $connection->close();
?>
