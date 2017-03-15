<!DOCTYPE html>
<html>

<head>
    <title> RaspberryPi Generic Controller </title>
    <link rel="stylesheet" type="text/css"  href="front_end_stylesheet.css"/>
    <script src="script.js"></script>
</head>

<body onload="onDocumentLoad()">
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

    <div class="header"> <strong>Generic Raspberry Pi Controller</strong> </div>
    <h2>Physical Pin Layout</h2>
    <table id="pinlayout">
        <tr>
            <td class="pin pwr_5v">xx</td>
            <td class="pin pwr_5v">xx</td>
            <td class="pin gnd">xx</td>
            <td class="pin gpio">14</td>
            <td class="pin gpio">15</td>
            <td class="pin gpio">18</td>
            <td class="pin gnd">xx</td>
            <td class="pin gpio">23</td>
            <td class="pin gpio">24</td>
            <td class="pin gnd">xx</td>
            <td class="pin gpio">25</td>
            <td class="pin gpio">8</td>
            <td class="pin gpio">7</td>
            <td class="pin id_eeprom">xx</td>
            <td class="pin gnd">xx</td>
            <td class="pin gpio">12</td>
            <td class="pin gnd">xx</td>
            <td class="pin gpio">16</td>
            <td class="pin gpio">20</td>
            <td class="pin gpio">21</td>
        </tr>
        <tr>
            <td class="pin pwr_3_3v">xx</td>
            <td class="pin gpio">2</td>
            <td class="pin gpio">3</td>
            <td class="pin gpio">4</td>
            <td class="pin gnd">xx</td>
            <td class="pin gpio">17</td>
            <td class="pin gpio">27</td>
            <td class="pin gpio">22</td>
            <td class="pin pwr_3_3v">xx</td>
            <td class="pin gpio">10</td>
            <td class="pin gpio">9</td>
            <td class="pin gpio">11</td>
            <td class="pin gnd">xx</td>
            <td class="pin id_eeprom">xx</td>
            <td class="pin gpio">5</td>
            <td class="pin gpio">6</td>
            <td class="pin gpio">13</td>
            <td class="pin gpio">19</td>
            <td class="pin gpio">26</td>
            <td class="pin gnd">xx</td>
        </tr>
    </table>

    <p id="table_color_key"> Color Key: Red = 5V, Black = GND, Orange = 3.3V, Blue = GPIO, White = EEPROM ID </p>
    <div id="pin_numbering_note"> <em>Note: The numbering system is utilizing the Broadcom SOC channel number and not the physical pin numbering. </em> </div>

    <h2> GPIO Control </h2>
    <div class="output_controls">
    <div class="alias_col_header"> Alias </div>
    <div class="phys_pin_col_header"> Pin Number </div>
    <div class="direction_col_header"> Input/Output </div>
    <div class="control_col_header"> Off/On </div>
    <div class="state_col_header"> State </div>
    </div>

    <?php
        foreach ($gpio_info as $current_gpio)
        {
            $cnt++;
            $gpio_num = ($current_gpio['id'] + 1); // GPIO Numbering starts at 2; add one to the ID to align them

            //Determine what the initial state of the checkbox should be
            if ($current_gpio['isHigh'])
            {
                $chkboxVal = "checked";
                $state = "High";
            }
            else
            {
                $chkboxVal = "";
                $state = "Low";
            }

            //shade every other row
            if ($cnt % 2)
            {
                $rowIsShaded = "shaded";
            }
            else
            {
                $rowIsShaded = "";
            }

            echo '<div class="output_controls ' .$rowIsShaded. '">
                <div class="alias_col">' .$current_gpio['name']. '</div>
                <div class="phys_pin_col">' .$gpio_num. '</div>

                <div class="direction_col">
                    <select class="direction_dropdown" data-id="'.$current_gpio['id'].'">';
                      if($current_gpio['isOutput'])
                      {
                        //default dropdown to output
                        echo '<option value="input">Input</option>
                              <option selected value="output">Output</option>';
                      }
                      else
                      {
                        //default dropdown to input
                        echo '<option selected value="input">Input</option>
                              <option value="output">Output</option>';
                      }

                      echo'
                      </select>
                </div>

                <div class="control_col">';

                if($current_gpio['isOutput'])
                  //enable the Off/On checkbox
                  echo'<input class="control_checkbox" id="control_checkbox_'.$current_gpio['id'].'" data-id="'.$current_gpio['id'].'" type="checkbox"'.$chkboxVal.'>';
                else
                  //disable the Off/On checkbox
                  echo'<input class="control_checkbox" id="control_checkbox_'.$current_gpio['id'].'" disabled data-id="'.$current_gpio['id'].'" type="checkbox"'.$chkboxVal.'>';
                echo'
                </div>
                <div class="state_col" id="state_'.$current_gpio['id'].'">'.$state.'</div>
                </div>';
        }
    ?>
    <br>
    <input class="button" type=button onClick="location.href='config.php'" value='Update Configurations'>
    <div class="footer"></div>
</body>
</html>
