<!DOCTYPE html>
<html>
    <head>
        <title> RaspberryPi Generic Controller Configuration </title>
        <link rel="stylesheet" type="text/css"  href="front_end_stylesheet.css"/>
        <script src="script.js"></script>
    </head>

    <body onload="onDocumentLoad()">
        <div class="header"> <strong>Generic Raspberry Pi Controller</strong> </div>
            <h2> GPIO Configurations </h2>
        <div class="config_updates">
            <div class="phys_pin_config_col_header">Pin Number</div>
            <div class="alias_config_col_header">Alias</div>
            <div class="pullup_enable_col_header">Pull Up Enable</div>
            <div class="pullup_type_col_header">Pull Up Type</div>
            <div class="display_enable_col_header">Display Enable</div>
        </div>

        <?php include "database_query.php";
        foreach($gpio_info as $current_gpio): ?>
            <?php
                $gpio_num = ($current_gpio['id'] + 1); // GPIO Numbering starts at 2; add one to the ID to align them
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
            ?>

            <div class="config_updates <?php echo $rowIsShaded;?>">
                <div class="phy_pin_config_col"><?php echo $current_gpio['id'];?></div>

                <?php $name = sprintf("gpio_alias_%d", $current_gpio['id']); ?>
                <div class="alias_config_col">
                    <input class="alias_input" type="text" name=<?php echo $name; ?> value="<?php echo $current_gpio['name']; ?>">
                </div>

                <?php
                    $id = sprintf("pullup_enable_checkbox_%d", $current_gpio['id']);
                    if ($current_gpio['isPulled'] == 1)
                    {
                        $chkboxVal = "checked";
                    }
                    else
                    {
                        $chkboxVal = "";
                    }
                ?>
                <div class="pullup_enable_col">
                     <input class="pullup_enable_checkbox" id="<?php echo $id;?>" data-id="<?php echo $current_gpio['id'];?>" type="checkbox" <?php echo $chkboxVal; ?>>
                </div>

                <?php
                    if ($current_gpio['pullType'] == 'Down')
                    {
                        $up_select = "value";
                        $down_select = "selected value";
                    }
                    else
                    {
                        $up_select = "selected value";
                        $down_select = "value";
                    }
                ?>
                <div class="pullup_type_col">
                     <select class="pullup_type_dropdown" data-id="<?php echo $current_gpio['id'];?>">
                        <option <?php echo $up_select;?>="Up">Pull-Up</option>
                        <option <?php echo $down_select;?>="Down">Pull-Down</option>
                     </select>
                </div>

                <?php
                    $id = sprintf("display_enable_checkbox_%d", $current_gpio['id']);
                    if ($current_gpio['isDisplayed'] == 1)
                    {
                        $chkboxVal = "checked";
                    }
                    else
                    {
                        $chkboxVal = "";
                    }
                ?>
                <div class="display_col">
                     <input class="display_enable_checkbox" id="<?php echo $id;?>" data-id="<?php echo $current_gpio['id'];?>" type="checkbox" <?php echo $chkboxVal; ?>>
                </div>
            </div>
        <?php endforeach; ?>

        <br>
        <input class="button" type=button onClick="location.href='index.php'" value='Return to GPIO Control'>
        <div class="footer"></div>
    </body>
</html>
