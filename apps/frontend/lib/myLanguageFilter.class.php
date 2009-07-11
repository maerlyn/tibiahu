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
      
      $domain = sfConfig::get("app_domain", $host);
      $host = $this->getContext()->getRequest()->getHost();
      if (!preg_match("/^(hu|en)\\." . preg_quote($domain) . "/i", $this->getContext()->getRequest()->getHost())) {
        $uri = $this->getContext()->getRequest()->getUri();
        $culture = $this->getContext()->getUser()->getCulture();
        $redir = str_replace("/{$host}/", "/{$culture}.{$domain}/", $uri);
        $this->getContext()->getController()->redirect($redir);
      }
      // execute next filter
      $filterChain->execute();
    }
    
  }