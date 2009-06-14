<?php
  
  class myLanguageFilter extends sfFilter
  {

    public function execute ($filterChain)
    {    
      // execute this filter only once
      if (preg_match("/^(hu|en)/i", $this->getContext()->getRequest()->getHost(), $matches)) {
        $this->getContext()->getUser()->setCulture($matches[1]);
        $this->getContext()->getUser()->isFirstRequest(false);
      }

      if ($this->getContext()->getUser()->isFirstRequest())
      {
        $culture = $this->getContext()->getRequest()->getPreferredCulture(array("hu", "en"));
        $this->getContext()->getUser()->setCulture($culture);
        $this->getContext()->getUser()->isFirstRequest(false);
      }
      // execute next filter
      $filterChain->execute();
    }
    
  }