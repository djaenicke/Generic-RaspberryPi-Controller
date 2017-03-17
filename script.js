/******************************************************************************
 * Event handler for when the Input/Output dropdown is changed
 ******************************************************************************/
var toggle_io_direction = function(e)
{
    var xmlhttp = new XMLHttpRequest();

    var chkbox = document.getElementById("control_checkbox_" + e.target.dataset.id);

    if (e.target.value == 'input')
    {
        chkbox.disabled = true; //Disable the Off/On checkbox if configured as input
    }
    else
    {
        chkbox.disabled = false; //Enable the Off/On checkbox if configured as output
    }

    xmlhttp.open("GET", "update_handler.php?id=" + e.target.dataset.id + "&direction=" + e.target.value, true);
    xmlhttp.send();

    xmlhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200)
        {
          console.log(this.responseText);
        }
    };
}

/******************************************************************************
 * Event handler for when the Off/On checkbox is changed
 ******************************************************************************/
var switch_output_state = function(e)
{
    var isHigh;
    var state = document.getElementById("state_" + e.target.dataset.id);

    if (e.srcElement.checked)
    {
        isHigh = 'true';
        state.innerText = "High" //Make the state match the checkbox value
    }
    else
    {
        isHigh = 'false';
        state.innerText = "Low" //Make the state match the checkbox value
    }

    var xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "update_handler.php?id=" + e.target.dataset.id + "&output_status=" + isHigh, true);
    xmlhttp.send();

    xmlhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            console.log(this.responseText);
        }
    };
}

/******************************************************************************
 * Event handler for when the update button is clicked
 ******************************************************************************/
var update_alias = function(e)
{
    var alias = document.getElementById("gpio_alias_" + e.target.dataset.id);;
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "update_handler.php?id=" + e.target.dataset.id + "&name=" + alias.value, true);
    xmlhttp.send();

    xmlhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            console.log(this.responseText);
        }
    };
}

/******************************************************************************
 * Event handler for when the Pull Up Enable checkbox is changed
 ******************************************************************************/
var enable_disable_pullup = function(e)
{
    var isEnabled;

    if (e.srcElement.checked)
    {
        isEnabled = 'true';
    }
    else
    {
        isEnabled = 'false';
    }

    var xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "update_handler.php?id=" + e.target.dataset.id + "&pull_enable=" + isEnabled, true);
    xmlhttp.send();

    xmlhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            console.log(this.responseText);
        }
    };
}

/******************************************************************************
 * Event handler for when the Pull Up Type dropdown is changed
 ******************************************************************************/
var toggle_pull_type = function(e)
{
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "update_handler.php?id=" + e.target.dataset.id + "&pull_type=" + e.target.value, true);
    xmlhttp.send();

    xmlhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200)
        {
          console.log(this.responseText);
        }
    };
}

/******************************************************************************
 * Event handler for when the Display Enable checkbox is changed
 ******************************************************************************/
var update_display_state = function(e)
{
    var isDisplayed;

    if (e.srcElement.checked)
    {
        isDisplayed = 'true';
    }
    else
    {
        isDisplayed = 'false';
    }

    var xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "update_handler.php?id=" + e.target.dataset.id + "&display_enable=" + isDisplayed, true);
    xmlhttp.send();

    xmlhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            console.log(this.responseText);
        }
    };
}

/******************************************************************************
 * Function for configuring the event handlers when the document is loaded
 ******************************************************************************/
function onDocumentLoad()
{
    var control_checkboxes = document.querySelectorAll('.control_checkbox');

    control_checkboxes.forEach(function(box){
    box.addEventListener('click', switch_output_state);
    });

    var direction_selectors = document.querySelectorAll('.direction_dropdown');

    direction_selectors.forEach(function(selector){
    selector.addEventListener('change', toggle_io_direction);
    });

    var update_alias_selectors = document.querySelectorAll('.update_button');

    update_alias_selectors.forEach(function(selector){
    selector.addEventListener('click', update_alias);
    });

    var pullup_enable_selectors = document.querySelectorAll('.pullup_enable_checkbox');

    pullup_enable_selectors.forEach(function(selector){
    selector.addEventListener('click', enable_disable_pullup);
    });

    var pullup_type_selectors = document.querySelectorAll('.pullup_type_dropdown');

    pullup_type_selectors.forEach(function(selector){
    selector.addEventListener('change', toggle_pull_type);
    });

    var display_enable_selectors = document.querySelectorAll('.display_enable_checkbox');

    display_enable_selectors.forEach(function(selector){
    selector.addEventListener('click', update_display_state);
    });

    setInterval(updatePage, 1000);
}

/******************************************************************************
 * Function for updating the GPIO's State periodically
 ******************************************************************************/
function updatePage()
{
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.open("GET", "gpio_state_api.php");
    xmlhttp.send();

    xmlhttp.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            myObj = JSON.parse(this.responseText);
            for (var key in myObj)
            {
                if (myObj.hasOwnProperty(key) && document.getElementById("state_" + key))
                {
                    var val = myObj[key];
                    if (val == 1)
                    {
                        document.getElementById("state_" + key).innerHTML = 'High';
                    }
                    else
                    {
                        document.getElementById("state_" + key).innerHTML = 'Low';
                    }
                }
            }
        }
    };
}
