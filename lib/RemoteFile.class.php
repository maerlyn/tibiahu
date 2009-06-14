<?php

abstract class RemoteFile
{
  
  /**
  * Fetches a file from a http server
  * 
  * @param string $url url to fetch
  * @param bool $isFallback is the call a fallback already?
  * @return string contents of the remote file
  */
  public static function get($url, $isFallback = false)
  {
    @set_time_limit(15);
    
    if (extension_loaded("curl")) {
      $c = curl_init();
      curl_setopt($c, CURLOPT_URL, $url);
      //curl_setopt($c, CURLOPT_MUTE, true);
      curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($c, CURLOPT_TIMEOUT, 10);
      
      $ret = curl_exec($c);
      curl_close($c);
      if ($ret == "" && !$isFallback) {
        $ret = self::safetyFallback($url);
      }
      
      return $ret;
    } 
    
    //fallback to the good ol' method
    $f = @fopen($url, "r");
    if (!$f) {
      return null;
    }
    $ret = stream_get_contents($f);
    fclose($f);
    if ($ret == "" && !$isFallback) {
      $ret = self::safetyFallback($url);
    }
    return $ret;
  }
  
  /**
  * Safety fallback called from the get function, replaces urls with ips in case the DNS dies on the server
  * 
  * @param string $url url to fetch
  * @return string contents of the remote file
  */
  private static function safetyFallback($url)
  {
    $replace = array(
      "http://www.tibia.com/"   =>  "http://62.146.78.198/"
    );
    
    $url = str_ireplace(array_keys($replace), array_values($replace), $url);
    return self::get($url, true);
  }
  
}