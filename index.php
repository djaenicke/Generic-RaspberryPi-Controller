<!DOCTYPE html>
<html>
    <head>
        <title> RaspberryPi Generic Controller </title>
        <link rel="stylesheet" type="text/css"  href="front_end_stylesheet.css"/>
        <script src="script.js"></script>
    </head>

    <body onload="onDocumentLoad()">
        <div class="header"> <strong>Generic Raspberry Pi Controller</strong> </div>

        <h2> Pinout </h2>
        <img src="gpio-pinout-pi2.png" height="13.4328%" width="45%">

        <h2> GPIO Control </h2>
        <div class="output_controls">
            <div class="alias_col_header"> Alias </div>
            <div class="phys_pin_col_header"> Pin Number </div>
            <div class="direction_col_header"> Input/Output </div>
            <div class="control_col_header"> Off/On </div>
            <div class="state_col_header"> State </div>
        </div>

        <?php include "database_query.php";
        foreach($gpio_info as $current_gpio): ?>
            <?php if($current_gpio['isDisplayed']): ?>
                <?php
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
                ?>

                <div class="output_controls <?php echo $rowIsShaded;?>">
                    <div class="alias_col"><?php echo $current_gpio['name'];?></div>
                    <div class="phys_pin_col"><?php echo $current_gpio['id'];?></div>

                    <?php
                        if ($current_gpio['isOutput'])
                        {
                            $input_select = "value";
                            $output_select = "selected value";
                        }
                        else
                        {
                            $input_select = "selected value";
                            $output_select = "value";
                        }
                    ?>
                    <div class="direction_col">
                        <select class="direction_dropdown" data-id="<?php echo $current_gpio['id'];?>">
                            <option <?php echo $input_select;?>="input">Input</option>
                            <option <?php echo $output_select;?>="output">Output</option>';
                        </select>
                    </div>

                    <?php
                        $id = sprintf("control_checkbox_%d", $current_gpio['id']);
                        if ($current_gpio['isOutput'])
                        {
                            $status = "";
                        }
                        else
                        {
                            $status = "disabled";
                        }
                    ?>
                    <div class="control_col">
                        <input class="control_checkbox" id="<?php echo $id;?>" data-id="<?php echo $current_gpio['id'];?>" <?php echo $status;?> type="checkbox" <?php echo $chkboxVal; ?>>
                    </div>

                    <?php $id = sprintf("state_%d", $current_gpio['id']);?>
                    <div class="state_col" id="<?php echo $id;?>"><?php echo $state;?></div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <br>
        <input class="button" type=button onClick="location.href='config.php'" value='Update Configurations'>
        <div class="footer"></div>
    </body>
</html>
