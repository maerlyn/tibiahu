$(document).ready(function() {

  $("#tabs").tabs({
    'cache': true,
    'spinner': "<img src='http://tibia.hu/images/spinner.gif' alt='...' />",
    'load': function(event, ui) {
      doDataTables();
    }
  });

});