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
    
    for ($i = 0; $i <= 10; ++$i) {
      @set_time_limit(10 * $i + 15);
      sleep($i * 5);
    
      if (extension_loaded("curl")) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        //curl_setopt($c, CURLOPT_MUTE, true);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_TIMEOUT, 10);
        
        $ret = curl_exec($c);
        $info = curl_getinfo($c);
        curl_close($c);
        if ("403" == ($status = $info["http_code"])) {
          continue;
        } else {
          break;
        }
        if ($ret == "" && !$isFallback) {
          $ret = self::safetyFallback($url);
        }
      } else { //no curl
      
        //fallback to the good ol' method
        $f = @fopen($url, "r");
        if (!$f) {
          return null;
        }
        $ret = stream_get_contents($f);
        fclose($f);
        if (stripos($ret, "403 forbidden")) {
          $status = "403";
          continue;
        } else {
          $status = "200";
          break;
        }
        if ($ret == "" && !$isFallback) {
          $ret = self::safetyFallback($url);
        }
      }
    }
    
    if ($status == "403") {
      return false;
    } else {
      if (false !== stripos($url, "http://www.tibia.com")) {
        $ret = iconv("ISO-8859-1", "UTF-8", $ret);
      }
      return $ret;
    }
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
      "http://www.tibia.com/"   =>  "http://62.146.78.198/",
      "http://tibia.com/"       =>  "http://62.146.78.198/"
    );
    
    $url = str_ireplace(array_keys($replace), array_values($replace), $url);
    return self::get($url, true);
  }
  
}