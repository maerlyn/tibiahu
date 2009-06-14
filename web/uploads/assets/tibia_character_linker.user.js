// ==UserScript==
// @name          Tibia Character Linker
// @namespace     http://tibia.hu/
// @description   Links to characters on character pages
// @include       http://www.tibia.com/community/?subtopic=character*
// @version       0.2
// @author        Tele (http://tibia.hu)
// ==/UserScript==

// --------------------------------------------------------------------
//
// This is a Greasemonkey user script.
//
// To install, you need FireFox http://www.mozilla.org/products/firefox and 
// the Firefox extension called Greasemonkey: http://greasemonkey.mozdev.org/
// Install the Greasemonkey extension then restart Firefox and revisit this script.
// Under Tools, there will be a new menu item to "Install User Script".
// Accept the default configuration and install.
//
// To uninstall, go to Tools/Manage User Scripts,
// select "Tibia Character Linker", and click Uninstall.
//
// --------------------------------------------------------------------

(function() {
  var trs = document.getElementsByTagName('tr');

  for (var i = 0; i < trs.length; i++) {
    if (trs[i].firstChild) {
      var fc = trs[i].firstChild;

      if (fc.innerHTML == 'Name:') {
        var slug = fc.nextSibling.textContent;
        var name = fc.nextSibling.textContent;
        slug = slug.replace(/ /g, "_").replace(/[^\w\d_]/g, "-").toLowerCase().replace(/[^-\w]/g, "");
        
        fc.nextSibling.innerHTML = '<a href="http://tibia.hu/hu/character/' + slug + '">' + name + '</a>';
      }
    }
  }
})();
