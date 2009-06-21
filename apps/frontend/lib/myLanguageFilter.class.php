<?php
  
  class myLanguageFilter extends sfFilter
  {

    public function execute ($filterChain)
    {    
      if (preg_match("/^(hu|en)\\./i", $this->getContext()->getRequest()->getHost(), $matches)) {
        $this->getContext()->getUser()->setCulture($matches[1]);
        $this->getContext()->getUser()->isFirstRequest(false);
      }
      
      if ($this->getContext()->getUser()->isFirstRequest())
      {
        $culture = $this->getContext()->getRequest()->getPreferredCulture(array("hu", "en"));
        $this->getContext()->getUser()->setCulture($culture);
        $this->getContext()->getUser()->isFirstRequest(false);
      }
      
      if (!preg_match("/^(hu|en)\\./i", $this->getContext()->getRequest()->getHost())) {
        $uri = $this->getContext()->getRequest()->getUri();
        $host = $this->getContext()->getRequest()->getHost();
        $culture = $this->getContext()->getUser()->getCulture();
        $redir = str_replace("/{$host}/", "/{$culture}.{$host}/", $uri);
        $this->getContext()->getController()->redirect($redir);
      }
      // execute next filter
      $filterChain->execute();
    }
    
  }