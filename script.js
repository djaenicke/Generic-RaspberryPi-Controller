var toggle_io_direction = function(e) {
  var xmlhttp = new XMLHttpRequest();

  var chkbox = document.getElementById("checkbox_" + e.target.dataset.id);

  if(e.target.value == 'input') {
    chkbox.disabled = true;
  }
  else {
    chkbox.disabled = false;
  }

  xmlhttp.open("GET", "update_io_direction.php?id=" + e.target.dataset.id + "&value=" + e.target.value, true);
  xmlhttp.send();

  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          console.log(this.responseText);
      }
  };
}

var switch_output_state = function(e) {
  var isHigh;

  if(e.srcElement.checked) {
    isHigh = 'true';
  }
  else {
    isHigh = 'false';
  }

  var xmlhttp = new XMLHttpRequest();

  xmlhttp.open("GET", "update_output_status.php?id=" + e.target.dataset.id + "&value=" + isHigh, true);
  xmlhttp.send();

  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          console.log(this.responseText);
      }
  };
}

function onDocumentLoad(){
  var checkboxes = document.querySelectorAll('.control_checkbox');

  checkboxes.forEach(function(box){
    box.addEventListener('click', switch_output_state);
  });

  var selectors = document.querySelectorAll('.direction_dropdown');

  selectors.forEach(function(selector){
    selector.addEventListener('change', toggle_io_direction);
  });
}
