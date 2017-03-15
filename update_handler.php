<?php
    //Create a map between key names and function handlers
    $key_handler_map = array("direction"      => "update_io_direction",
                             "output_status"  => "update_output_status",
                             "name"           => "update_name",
                             "pull_enable"    => "update_pull_enable",
                             "pull_type"      => "update_pull_type",
                             "display_enable" => "update_display_enable");

    //Get the id value
    if (array_key_exists('id', $_REQUEST))
    {
        $id = $_REQUEST['id'];

        if (($id > 26) || ($id < 1))
        {
            //Invalid id value received, exit
            print("Failure");
            exit;
        }
    }
    else
    {
        //No id value received, exit
        print("Failure");
        exit;
    }

    //Iterate over each key/value pair
    foreach ($_REQUEST as $key=>$value)
    {
        if (array_key_exists($key, $key_handler_map))
        {
            call_user_func(array('handlers', $key_handler_map[$key]), $id, $value);
        }
    }

    //All finished
    print("Success");
    exit;

    class handlers
    {
        static function update_io_direction($id, $val)
        {
            if ($val == "output")
            {
                update_database_integer("UPDATE GPIO SET isOutput=1 WHERE id=$id");
            }
            elseif ($val == "input")
            {
                update_database_integer("UPDATE GPIO SET isOutput=0 WHERE id=$id");
            }
            else
            {
                print("Failure");
                exit;
            }
        }

        static function update_output_status($id, $val)
        {
            if ($val == "true")
            {
                update_database_integer("UPDATE GPIO SET isHigh=1 WHERE id=$id");
            }
            elseif ($val == "false")
            {
                update_database_integer("UPDATE GPIO SET isHigh=0 WHERE id=$id");
            }
            else
            {
                print("Failure");
                exit;
            }
        }

        static function update_name($id, $val)
        {
            //Establish a connection to the database
            require 'login.php';
            $connection = new mysqli($hostname, $username, $password, $database);
            if ($connection->connect_error) die($connection->connect_error);

            $name = $connection->real_escape_string($val);
            $query = sprintf("UPDATE GPIO SET name='%s' WHERE id=%d", $name, $id);

            $result = $connection->query($query);
            if (!$result) die($connection->connect_error);

            $connection->close();
        }

        static function update_pull_enable($id, $val)
        {
            if ($val == "true")
            {
                update_database_integer("UPDATE GPIO SET isPulled=1 WHERE id=$id");
            }
            elseif ($val == "false")
            {
                update_database_integer("UPDATE GPIO SET isPulled=0 WHERE id=$id");
            }
            else
            {
                print("Failure");
                exit;
            }
        }

        static function update_pull_type($id, $val)
        {
            //Establish a connection to the database
            require 'login.php';
            $connection = new mysqli($hostname, $username, $password, $database);
            if ($connection->connect_error) die($connection->connect_error);

            $pull_type = $connection->real_escape_string($val);
            $query = sprintf("UPDATE GPIO SET pullType='%s' WHERE id=%d", $pull_type, $id);

            $result = $connection->query($query);
            if (!$result) die($connection->connect_error);

            $connection->close();
        }

        static function update_display_enable($id, $val)
        {
            if ($val == "true")
            {
                update_database_integer("UPDATE GPIO SET isDisplayed=1 WHERE id=$id");
            }
            elseif ($val == "false")
            {
                update_database_integer("UPDATE GPIO SET isDisplayed=0 WHERE id=$id");
            }
            else
            {
                print("Failure");
                exit;
            }
        }
    }

    function update_database_integer($query)
    {
        //Establish a connection to the database
        require 'login.php';
        $connection = new mysqli($hostname, $username, $password, $database);
        if ($connection->connect_error) die($connection->connect_error);

        $result = $connection->query($query);
        if (!$result) die($connection->connect_error);

        $connection->close();
    }
?>
