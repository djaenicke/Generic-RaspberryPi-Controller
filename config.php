<!DOCTYPE html>
<html>

<head>
  <title> RaspberryPi Generic Controller Configuration </title>
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

<h2> GPIO Configurations </h2>
  <div class="config_updates">
    <div class="alias_config_col_header">Alias</div>
    <div class="pullup_enable_col_header">Pull Up Enable</div>
    <div class="pullup_type_col_header">Pull Up Type</div>
    <div class="display_enable_col_header">Display Enable</div>
  </div>

  <?php
    foreach ($gpio_info as $current_gpio)
    {
      $cnt++;

      //shade every other row
      if ($cnt % 2)
      {
        $rowIsShaded = "shaded";
      }
      else
      {
        $rowIsShaded = "";
      }

      echo'<div class="config_updates ' .$rowIsShaded. '">
              <div class="alias_config_col">
                 <input class="alias_input" type="text" name="gpio_alias_'.$current_gpio['id'].'">
              </div>';
         echo'<div class="pullup_enable_col">
                 <input class="pullup_enable_checkbox" id="pullup_enable_checkbox_'.$current_gpio['id'].'" data-id="'.$current_gpio['id'].'" type="checkbox"'.$chkboxVal.'>
              </div>';
         echo'<div class="pullup_type_col">
                 <select class="pullup_type_dropdown" data-id="'.$current_gpio['id'].'">
                    <option selected value="pullup">Pull-Up</option>
                    <option value="pulldown">Pull-Down</option>
                 </select>
              </div>';
         echo'<div class="display_col">
                 <input class="display_enable_checkbox" id="display_enable_checkbox_'.$current_gpio['id'].'" data-id="'.$current_gpio['id'].'" type="checkbox"'.$chkboxVal.'>
              </div>
            </div>';
    }
  ?>

  <br>
  <input class="button" type=button onClick="location.href='index.php'" value='Return to GPIO Control'>
  <div class="footer"></div>
</body>
</html>
