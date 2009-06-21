<?php
  
class generalComponents extends sfComponents
{
  public function executeQuicksearch(sfWebRequest $request)
  {
    $this->form = new SearchForm(array(), array("isQuickSearch"=>true));
  }
  
  public function executeLanguage(sfWebRequest $request)
  {
    $url = $request->getUri();
    $url = str_replace(array("/en/", "/hu/"), "", $url);
    $this->url_en = str_replace("http://hu.", "http://en.", $url);
    $this->url_hu = str_replace("http://en.", "http://hu.", $url);
  }
    
}