<?php
    include "database_query.php";

    $gpio_state = array();

    foreach($gpio_info as $current_gpio)
    {
        //Build an associative array as id->val with val being either 0 or 1
        $gpio_state[$current_gpio['id']] = $current_gpio['isHigh'];
    }

    header('Content-type: application/json');
    echo json_encode($gpio_state);
    exit;
?>
