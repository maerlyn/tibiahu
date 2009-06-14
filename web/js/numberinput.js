$(document).ready(function(){

  $("input.number").blur(function(){
    if (invalid(num2str ($.trim ($(this).val())))) {
      $(this).val(num2str($.trim($(this).val())));
      $(this).addClass("error");
    } else {
      $(this).val(str2num(num2str($.trim($(this).val()))));
      $(this).removeClass("error");
    }
  });
  
  $("input.number").focus(function() {
    $(this).val(num2str($(this).val()));
    $(this).select();
  });
  
});

function invalid (string)
{
  return string.replace (/([0-9])/gi, "").length;
}

function reverse (string)
{
  return string.split ("").reverse ().join ("");
}

function str2num (string)
{
  return reverse (reverse (string).replace (/([0-9]{3})/gi, "\$1 ").replace (/( )$/, "").replace (/( \-)/, "-"));
}

function num2str (string)
{
  return string.replace (/( )/gi, "");
}

