$(document).ready(function() {
  $("#blessingform").submit(function(event) {
    $("#ajaxresult").html("...");
    $.post(
      $("#blessingform").attr("action"),
      { "calculator_blessing[level]": $("#blessingform input:first-child").attr("value") },
      function (data, textStatus) {
        $("#ajaxresult").html(data);
      },
      "html"
    );
    event.preventDefault();
  });
});