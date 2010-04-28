<?php

function thu_menu($name, $uri, $options = array())
{
  echo "<!-- " . sfContext::getInstance()->getRouting()->getCurrentInternalUri(true) . " " . $uri . "--> ";
  return link_to_unless(strpos(sfContext::getInstance()->getRouting()->getCurrentInternalUri(true), $uri) !== false, $name, $uri, $options);
}
