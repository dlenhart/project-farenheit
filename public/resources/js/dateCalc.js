  function dateDiff(str1) {

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
      dd = '0' + dd
    }

    if (mm < 10) {
      mm = '0' + mm
    }

    today = mm + '/' + dd + '/' + yyyy;
    //document.write(today);
    var diff = Math.floor((Date.parse(today) - Date.parse(str1)) / 86400000);
    return diff;
    
  }
