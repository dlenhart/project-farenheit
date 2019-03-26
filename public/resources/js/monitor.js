/**
*
* monitor.js
*
*/

// Round function
function round(value, decimals) {
  return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}

// Get current temperature via test api
function getTemperature() {
  // ajax call to /api/get/temperature
  $('#temp_spinner').show();
  $('#status_spinner').show();
  //clear any text
  $('#sys_status').html('');
  $('#currentTemp').html('');
  //hide alert for fresh call
  $('#fileAlert').hide();

  $.get("/api/get/temperature", function(data) {
    console.log(data);
    //clear if anything
  }).done(function(data) {
    $status = data.status;
    $temp = round(data.ferenheit, 2); //lets use F
    console.log($temp);
    if ($temp === null || isNaN($temp)) {
      $tmp = "<em>NULL</em>";
    } else {
      $tmp = $temp;
    }

    if ($status === 'YES') {
      $out = "ONLINE";
      //kind of redundant, but only 3 combinations i guess....
      $('#systemTitle').removeClass('red'); //remove red?
      $('#systemTitle').removeClass('grey'); //remove grey?
      $('#systemTitle').addClass('green'); //set green class
    } else if ($status === 'NO' || $status === 'ERR') {
      $out = "OFFLINE";
      $('#systemTitle').removeClass('green'); //remove green?
      $('#systemTitle').removeClass('grey'); //remove grey?
      $('#systemTitle').addClass('red'); //set red class
      //pop the alert with new message
      $('#fileAlert').fadeIn("slow");

      $('#fileAlert').html("<strong>WARNING!</strong>");
      $('#fileAlert').append("There appears to be an issue with the thermometer - ");
      $('#fileAlert').append(data.timestamp + " - attempts: <b>" + data.attempts + "</b>");

      if (data.msg) {
        console.log('bad file');
        //pop alert
        $('#fileAlert').fadeIn("slow");
        $('#fileAlert').html("<strong>WARNING!</strong> " + data.msg);
        $tmp = 'ERR';
        $out = data.status;
      }
    } else {
      $out = data.status;
    }
    //write to span
    $('#sys_status').html($out);
    //update temperature box
    $('#currentTemp').html($tmp);
    //hide Spinners
    $('#status_spinner').hide();
    $('#temp_spinner').hide();
  });
}

// Call uptime after the page loads.
function getUptime() {
  $.get("/api/get/uptime", function(data) {
    console.log(data.DateConverted);
    //clear if anything
    $('#systemUptime').html("");

  }).done(function(data) {
    //write to span
    $('#systemUptime').html(data.DateConverted);
    //hide Spinner
    $('#active_spinner').hide();
  });
}

// Document load.
$(document).ready(function() {
  getTemperature(); //call temp on page load
  setTimeout(function() {
    //set timeout on this call
    getUptime();
  }, 1000); // milliseconds
});
