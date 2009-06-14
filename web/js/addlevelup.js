$(document).ready(function() {
  $("#editlevelup").jqm({
    ajax: "@href",
    ajaxText: "<img src='" + $("img.ajaxloader").attr("src") + "' alt='...' />",
    modal: false,   
    trigger: "a.addlevelup",
    
    onLoad: makeOKbuttonSave
  });
});

function makeOKbuttonSave()
{
  $("#levelup-ok").click(function(event) {
    $.post($("#levelup-url").attr("value"), {
      reason: $("#levelup-reason").attr("value")
    }, function(data) {
      $("#editlevelup").html(data);
      makeOKbuttonSave();
    });
  });
}
