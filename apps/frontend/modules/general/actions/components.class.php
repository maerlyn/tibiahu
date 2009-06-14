<?php
  
class generalComponents extends sfComponents
{
  public function executeQuicksearch(sfWebRequest $request)
  {
    $this->form = new SearchForm(array(), array("isQuickSearch"=>true));
  }
  
  public function executeLanguage(sfWebRequest $request)
  {
  }
    
}