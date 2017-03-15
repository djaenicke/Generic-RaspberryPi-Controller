/******************************************************************************
 * Event handler for when the dropdown is changed
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
 * Event handler for when the checkbox is changed
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
}
