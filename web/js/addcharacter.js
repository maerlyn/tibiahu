$(document).ready(function(){

  $("#addlink").removeClass("hidden");
  $("#addlink").click(function(event) {

    event.preventDefault();
    if ($("#addlink").hasClass("expanded")) {
      $("#addlink").removeClass("expanded");
      $("#addbox").addClass("hidden");
    } else {
      $("#addlink").addClass("expanded");
      $("#addbox").removeClass("hidden");

    }

  });

  $("#get-code").click(function(e) {
   e.preventDefault();
   if ($("#input-charname").val() != "") {

     showLoader();
     $("#ajaxcontainer-code").load(
      $("#get-code").attr("href"),
      { "charname": $("#input-charname").val() },
      function () {
        hideLoader();
        $("#ajax-verify").click(function(event) {
          event.preventDefault();
          showLoader();
          $("#ajaxcontainer-verify").load(
            $("#ajax-verify").attr("href"),
            { "charname": $("#input-charname").val() },
            function () { hideLoader(); }
          );
        });
      }
    );
   }

  });

});

function showLoader() {
  $("#addbox .loader").removeClass("hidden");
}

function hideLoader() {
  $("#addbox .loader").addClass("hidden");
}
