<?php

/**
* Accessor functions for the Tibia webstie
* 
* @package tibiahu
* @author Maerlyn <maerlyng@gmail.com>  
*/
abstract class TibiaWebsite
{
  
  private static $characterInfo_cache = array();
  
  /**
  * Gets the data of the currently online characters
  * 
  * @param string name of the world
  * @return array characters with name, level and vocation
  */
  public static function whoIsOnline($world = "Secura")
  {
    $world = ucfirst($world);
    if (false === ($website = RemoteFile::get("http://www.tibia.com/community/?subtopic=whoisonline&world={$world}"))) {
      return array();
    }

    $website = str_ireplace("&#160;", " ", $website);

    libxml_use_internal_errors(true);
    $domd = new DOMDocument("1.0", "iso-8859-1");
    $domd->loadHTML($website);
    libxml_use_internal_errors(false);

    $domx = new DOMXPath($domd);
    $rows = $domx->query("//div[@class='BoxContent']/table//table[position() = 2]//tr[position() > 1]");

    $return = array();
    foreach ($rows as $char) {
      set_time_limit(5);
      $return[] = array(
        "name"      =>  $char->childNodes->item(0)->textContent,
        "level"     =>  $char->childNodes->item(1)->textContent,
        "vocation"  =>  $char->childNodes->item(2)->textContent,
      );
    }

    return $return;
  }
  
  /**
  * Gets the character information for a character
  * 
  * @param string name of the character
  * @return array the characters data, empty array if tibia.coms down, null if the char does not exist
  */
  public static function characterInfo($charname)
  {
    if (isset(self::$characterInfo_cache[$charname])) {
      return self::$characterInfo_cache[$charname];
    }
    
    if (
        false === ($website = RemoteFile::get("http://www.tibia.com/community/?subtopic=character&name=" . urlencode($charname))) ||
        strlen($website) < 512
       ) {
      return null;
    }

    if (stripos($website, "</B> does not exist.</TD>")) {
      return null;
    }

    $website = str_ireplace("&#160;", " ", $website);

    libxml_use_internal_errors(true);
    $domd = new DOMDocument("1.0", "iso-8859-1");
    $domd->loadHTML($website);
    libxml_use_internal_errors(false);

    $domx = new DOMXPath($domd);
    $table = $domx->query("//td[child::b='Character Information']/ancestor::table[1]")->item(0);

    $character = array(
      "name"        =>  $domx->query("//tr[child::td='Name:']/td[2]", $table)->item(0)->textContent,
      "sex"         =>  $domx->query("//tr[child::td='Sex:']/td[2]", $table)->item(0)->textContent,
      "profession"  =>  $domx->query("//tr[child::td='Profession:']/td[2]", $table)->item(0)->textContent,
      "level"       =>  $domx->query("//tr[child::td='Level:']/td[2]", $table)->item(0)->textContent,
      "world"       =>  $domx->query("//tr[child::td='World:']/td[2]", $table)->item(0)->textContent,
      "residence"   =>  $domx->query("//tr[child::td='Residence:']/td[2]", $table)->item(0)->textContent,
      "lastlogin"   =>  strtotime($domx->query("//tr[child::td='Last login:']/td[2]", $table)->item(0)->textContent),
      "accountstatus" =>  $domx->query("//tr[child::td='Account Status:']/td[2]", $table)->item(0)->textContent,
    );

    $married = $domx->query("//tr[child::td='Married to:']/td[2]", $table);
    if ($married->length) {
      $character["married_to"] = $married->item(0)->textContent;
    }

    $guild = $domx->query("//tr[child::td='Guild membership:']/td[2]", $table);
    if ($guild->length) {
      $name = $domx->query("//tr[child::td='Guild membership:']/td[2]//a", $table)->item(0)->textContent;
      $rank = substr($guild->item(0)->textContent, 0, -1 * (strlen($name) + strlen(" of the ")));
      $character["guild"] = array(
        "name"  =>  $name,
        "rank"  =>  $rank,
      );
    }

    $position = $domx->query("//tr[child::td='Position:']/td[2]", $table);
    if ($position->length) {
      $character["position"] = $position->item(0)->textContent;
    }

    $house = $domx->query("//tr[child::td='House:']/td[2]", $table);
    if ($house->length) {
      $house = $house->item(0)->textContent;
      $character["house"] = substr($house, 0, strpos($house, " is paid until"));
    }

    $character["is_hidden"] = (false === stripos($website, "<B>Account Information</B>") && false === stripos($website, "<B>Characters</B>"));

    $character["deaths"] = self::processDeaths($website);

    $characters = $domx->query("//td[child::b='Characters']/ancestor::table[1]");
    if ($characters->length) {
      $character["characters"] = array();
      $rows = $domx->query("tr[position() > 2]", $characters->item(0));
      foreach($rows as $row) {
        $character["characters"][] = array(
          "name"  =>  preg_replace("@^(\\d+\\. )@is", "", $row->childNodes->item(0)->textContent),
          "world" =>  $row->childNodes->item(1)->textContent,
        );
      }
    }

    // some cleanup

    if (stripos($character["name"], ", will be deleted at")) {
      $tmp = explode(", will be deleted at", $character["name"]);
      $character["name"] = $tmp[0];
      $character["deleted"] = strtotime(str_replace("&#160;", " ", $tmp[1]));
    }

    
    self::$characterInfo_cache[$charname] = $character;
    return $character;
  }
  
  /**
  * Gets the info for the last death of the given character
  * 
  * @param string name of the character
  * @return array the death data (time, reason), empty array if tibia.com is down or theres no death, null if the char is nonexistent
  */
  public static function lastDeath($charname)
  {
    if (false === ($website = RemoteFile::get("http://www.tibia.com/community/?subtopic=character&name=" . urlencode($charname)))) {
      return null;
    }
    
    if (stripos($website, "</B> does not exist.</TD>")) {
      return null;
    }
    
    $deaths = self::processDeaths($website);
    
    return isset($deaths[0]) ? $deaths[0] : array();
  }
  
  /**
  * Gets the death list for a given character
  * 
  * @param string name of character
  * @return array the death data (time, reason), empty array if tibia.com is down or theres no death, null if char doesnt exist
  */
  public static function getDeaths($charname)
  {
    if (false === ($website = RemoteFile::get("http://www.tibia.com/community/?subtopic=character&name=" . urlencode($charname)))) {
      return null;
    }
    
    if (false !== stripos($website, "</B> does not exist.</TD>")) {
      return null;
    }
    
    return self::processDeaths($website);
  }
  
  /**
  * Extracts the deaths from the website
  * 
  * @param string the character page 
  * @return array deaths
  */
  private static function processDeaths($website)
  {
    $deaths = array();
    
    if (preg_match("@<td colspan=\"2\" class=\"white\" ><b>Character Deaths</b></td>.+?</table>@is", $website, $matches)) 
    {
      $deathlist = $matches[0];
      
      preg_match_all("@<tr(.+?)</tr>@is", $deathlist, $rows);
      
      foreach ($rows[0] as $row) {
       
        if (false !== stripos($row, "?subtopic=characters")) { //killed by a player
          $death["reason_type"] = "player";
          $death["reason"] = array();
          
          preg_match_all("@&name=.+?>(.+?)</a>@is", $row, $deathdata);
          $reasons = array();
          foreach ($deathdata[1] as $v) {
            $reasons[] = str_replace("&#160;", " ", $v);
          }
          
          preg_match("@<td w.+?>(.+?)</td><td>.+? at level (\\d+)@is", $row, $deathdata);
          
          $death = array(
            "time"        =>  strtotime(str_replace("&#160;", " ", $deathdata[1])),
            "level"       =>  $deathdata[2],
            "reason_type" =>  "player",
            "reason"      =>  $reasons,
          );
          
        } else { // killed by a monster
        
          preg_match("@<tr bgcolor=\"#.{6}\" ><td w.+?>(.+?)</td><td>(?:Killed |Died) at Level (\\d+) by (.+?)\.</td></tr>@is", $row, $deathdata);
          //die(var_dump($deathdata));
          $reason = trim(preg_replace('#^(an |a )(.+?)$#i', "\\2", $deathdata[3]));
          if (false !== stripos($reason, " of <a ")) {
            $reason = explode(" of <a ", $reason);
            $reason = $reason[0];
          }
          
          $death = array(
            "time"        =>  strtotime(str_replace("&#160;", " ", $deathdata[1])),
            "level"       =>  $deathdata[2],
            "reason_type" =>  "monster",
            "reason"      =>  $reason,
          );
        }
        
        $deaths[] = $death;
      }
    }
    
    return $deaths;
  }
  
  /**
  * Checks if the given character exists
  * 
  * @param string name of the character
  * @return boolean 
  */
  public static function characterExists($charname)
  {
    if (false === ($website = RemoteFile::get("http://www.tibia.com/community/?subtopic=character&name=" . urlencode($charname)))) {
      return null;
    }
    return (false === stripos($website, "</B> does not exist.</TD>"));
  }
  
  /**
  * Gets the guild list for a given world
  *
  * @param string world
  * @return array guilds
  */
  public static function getGuildList($world)
  {
    $world = ucfirst($world);
    if (false === $website = RemoteFile::get("http://www.tibia.com/community/?subtopic=guilds&world=" . urlencode($world))) {
      return null;
    }
    
    preg_match_all("#<img src=\"http://static.tibia.com/images/(?:guildlogos|community)/.+?\.gif\".+?><b>(.+?)</b>#is", $website, $matches);
    
    return str_replace("&#160;", " ", $matches[1]);
  }
  
  /**
  * Gets the members of the given guild
  *
  * @param string name of the guild
  * @return array guild members
  */
  public static function getGuildMembers($guild)
  {
    if (false === ($website = RemoteFile::get("http://www.tibia.com/community/?subtopic=guilds&page=view&GuildName=" . urlencode($guild)))) {
      return null;
    }

    #preg_match_all("#<b>Guild Members</b></td></tr>(.+?)</table>#is", $website, $matches);
    preg_match_all("#<a href=\"http://www.tibia.com.+?subtopic=characters.+?name=.+?\">(.+?)</a>#is", $website /*$matches[1][0]*/, $matches);
    return str_replace("&#160;", " ", $matches[1]);
  }
  
  /**
  * Verifies the existence of the given code in a characters comment field
  *
  * @param string name of the character
  * @param string code to verify
  * @return bool
  */
  public static function verifyCode($character, $code)
  {
    if (false === ($website = RemoteFile::get("http://www.tibia.com/community/?subtopic=character&name=" . urlencode($character)))) {
      return null;
    }
    
    if (false !== stripos($website, "</B> does not exist.</TD>")) {
      return false;
    }
    
    if (!preg_match("#<TD VALIGN=top>Comment:</TD><TD>(.+?)</TD></TR>#is", $website, $matches)) { //has no comment
      return false;
    }
    
    return (false !== strpos($matches[1], $code));
  }
 
  /**
  * Gets the list of creatures from tibia.wikia.com
  * 
  * @return array list of creatures
  */
  public static function getListOfCreatures()
  {
    if (false === ($website = RemoteFile::get("http://tibia.wikia.com/wiki/List_of_Creatures"))) {
      return null;
    }
/*    <tr>
<td><a href="/wiki/Adept_of_the_Cult" title="Adept of the Cult">Adept of the Cult</a>*/

    preg_match_all("#<tr>\n<td><a href=\"/wiki/.+?\" title=\".+?\">(.+?)</a>\n</td>#is", $website, $matches);
    
    return $matches[1];
  }
 
 /**
 * Returns an array of all gameworlds
 * 
 * @return array gameworlds
 */
  public static function getWorlds()
  {
    return array("Aldora", "Amera" ,"Antica" ,"Arcania", "Askara" ,"Astera",
                 "Aurea", "Azura", "Balera", "Berylia", "Calmera", "Candia",
                 "Celesta", "Chimera", "Danera", "Danubia", "Dolera", "Elera",
                 "Elysia", "Empera", "Eternia", "Fortera", "Furora", "Galana",
                 "Grimera", "Guardia", "Harmonia", "Hiberna", "Honera", "Inferna",
                 "Iridia", "Isara", "Jamera", "Julera", "Keltera", "Kyra", 
                 "Libera", "Lucera", "Luminera", "Lunara", "Malvera", "Menera",
                 "Morgana", "Mythera", "Nebula", "Neptera", "Nerana", "Nova",
                 "Obsidia", "Ocera", "Pacera", "Pandoria", "Premia", "Pythera",
                 "Refugia", "Rubera", "Samera", "Saphira", "Secura", "Selena",
                 "Shanera", "Shivera", "Silvera", "Solera", "Tenebra", "Thoria",
                 "Titania", "Trimera", "Unitera", "Valoria", "Vinera", "Xantera",
                 "Xerena", "Zanera");
  }
 
 /**
 * Returns a cleaned version of a given article
 * 
 * @param string
 * @return string
 */
 private static function articleCleanup($input)
 {
   $replace = array(
    "<br>"      =>  "<br />",
    "<center>"  =>  "",
    "</center>" =>  "",
    "<u>"       =>  "<span class=\"underline\">",
    "</u>"      =>  "</span>",
    "<href"     =>  "<a href",
    "<p>"       =>  ""
   );
   
   $ret = preg_replace("#<img src=\"http://static\\.tibia\\.com/images/global/letters/letter_martel_(.)\\.gif\" BORDER=0 ALIGN=bottom>#is", 
      "\\1", $input); //first letters

   /*$ret = preg_replace("#<img src=\"(.+?)\".+?(?:align=([\"']?)(left|right)\\2).+?>(?:</a>|)#is", 
      "<img src=\"\\1\" alt=\"\" class=\"\\3\" /><br />", $ret); //images*/
      
   preg_match_all("#<img.+?>(</a>|)#is", $ret, $matches);
   foreach ($matches[0] as $k => $v) {
     $src = preg_replace("#.+?src=\"(.+?)\".*#is", "\\1", $v);
     
     if (false !== strpos($v, "align=")) { //has an align
       $class = preg_replace("#.+?align=(([\"']?)(left|right)([\"']?)).*#is", "\\3", $v);
     } else {
       $class = "center";
     }
     
     if (false !== stripos($v, "onclick")) {
       $link = preg_replace("#.+?onclick=.window\\.open\\('(.+?)'\\,.*#is", "\\1", $v);
     } else {
       $link = false;
     }
     
     $repl = "<img src=\"" . $src . "\" class=\"" . $class . "\" alt=\"\" />";
     if ($link) {
       $repl = sprintf("<a href=\"%s\">%s</a>", $link, $repl);
     }
     if ($class == "center") {
       $repl .= "<br />";
     }
     $ret = str_replace($v, $repl, $ret);
   }      
      
   return 
    str_replace(array_keys($replace), array_values($replace), 
    preg_replace("#&(?!amp;)#is", "&amp;",
      $ret
    ))
    ;
 }
 
  /**
  * Returns the current newsticker elements
  * 
  * @return array newsticker
  */
  public static function getNewsticker()
  {
    if (false === ($website = RemoteFile::get("http://www.tibia.com/news/?subtopic=latestnews"))) {
      return null;
    }
    
    if (!preg_match("#<div id=\"newsticker\"(.+?)<div id=\"featuredarticle\"#is", $website, $matches)) {
      return null;
    }
    $newsticker = $matches[1];
    
    preg_match_all("#<div id='TickerEntry-\\d(.+?)</div>[\n ]+?</div>#is", $newsticker, $matches);
    
    $items = array();
    foreach ($matches[0] as $v) {
      $item = array(
        "date"  =>  strtotime(str_replace("&#160;", " ", preg_replace("@.+?<span class='NewsTickerDate'>(.+?)&#160;-.*@is", "\\1", $v))),
        "body"  =>  str_replace(array("\n", "\r"), array("", " "), preg_replace("#.+?class='NewsTickerFullText'>(.+?)<.div>.*#is", '\1', $v))
      );
      $items[] = $item;
    }
    
    return $items;
  }
  
  /**
  * Returns the current latest news
  * 
  * @return array news
  */
  public static function getLatestNews()
  {
    if (false === ($website = RemoteFile::get("http://www.tibia.com/news/?subtopic=latestnews"))) {
      return null;
    }
    
    if (!preg_match_all(
      "#<div class='NewsHeadline'>(.+?)</tr></table><br/>#is",
      $website,
      $matches
    )) {
      return null;
    }
    
    $items = array();
    foreach ($matches[0] as $v) {
      //echo $v;break;
      $item = array(
        "date"  =>  strtotime(str_replace("&#160;", " ", preg_replace("#.+?<div class='NewsHeadlineDate'>(.+?) - .*#is", "\\1", $v))),
        "title" =>  preg_replace("#.+?<div class='NewsHeadlineText'>(.+?)</div>.*#is", "\\1", $v),
        "body"  =>  self::articleCleanup(preg_replace("#.+?<tr>.+?<td.+?>(.+?)</td>.+?</tr></table>.*#is", "\\1", $v))
      );
      $items[] = $item;
    }
    return $items;
  }
  
  /**
  * Returns the current featured article
  * 
  * @return array
  */
  public static function getFeaturedArticle()
  {
    if (false === ($website = RemoteFile::get("http://www.tibia.com/news/?subtopic=latestnews"))) {
      return null;
    }
    
    if (!preg_match(
      "#<div id='TeaserThumbnail'><a href='http://www.tibia.com/news/.subtopic=latestnews&amp;id=(\d+)'><img#is",
      $website,
      $matches
    )) {
      //error
      return null;
    }
    
    $website = RemoteFile::get("http://www.tibia.com/news/?subtopic=latestnews&id=" . $matches[1]);
    preg_match("#<div id=\"featuredarticle\".+?<script#is", $website, $matches);
    
    $article = array(
      "date"  =>  strtotime(str_replace("&#160;", " ", preg_replace("#.+?<div class='NewsHeadlineDate'>(.+?) - </div>.*#is", "\\1", $matches[0]))),
      "title" =>  preg_replace("#.+?<div class='NewsHeadlineText'>(.+?)</div>.*#is", "\\1", $matches[0]),
      "body"  =>  self::articleCleanup(preg_replace("#.+?<table.+?<tr>[\n ]+?<td.+?>(.+?)</td>[\n ]+?</tr><tr><td><div.*#is", "\\1", $matches[0]))
    );
    
    return $article;
  }
  
  /**
  * Returns the active polls
  * 
  * @return array
  */
  public static function getActivePolls()
  {
    if (false === ($website = RemoteFile::get("http://www.tibia.com/community/?subtopic=polls"))) {
      return null;
    }
    
    preg_match("#<b>Active Polls</b>(.+?)</table>#is", $website, $matches);
    
    preg_match_all("#<tr.+?>(.+?)</tr>#is", $matches[1], $matches);
    unset($matches[1][0]);
    
    $polls = array();
    foreach ($matches[1] as $v) {
      preg_match("#<td><a href='(.+?)'>(.+?)</a></td><td>(.+?)</td>#is", $v, $pollmatches);
      $poll = array(
        "url"   =>  $pollmatches[1],
        "title" =>  $pollmatches[2],
        "end"   =>  strtotime(str_replace("&#160;", " ", $pollmatches[3]))
      );
      $polls[] = $poll;
    }
    
    return $polls;
  }
  
  /**
  * Fetches the killstats for the given world, returns them in a nice array
  * 
  * @param string worlds name
  * @return array the killstats
  */
  public static function getKillStatistics($world = "Secura")
  {
    if (false === ($website = RemoteFile::get("http://www.tibia.com/community/?subtopic=killstatistics&world=" . ucfirst($world)))) {
      return null;
    }
    
    preg_match("#<table.+?width=100%>.+?<b>Last Day</b>.+?<b>Last Week</b>(.+?)</table>#is", $website, $matches);
    preg_match_all("#<tr.+?>(.+?)</tr>#is", $matches[1], $matches);
    $matches[0] = str_replace("&#160;", "", $matches[0]);
    
    $killstats = array();
    foreach($matches[0] as $v) {
      if (false !== stripos($v, "#505050")) {
        continue; // skip if it's a header tr
      }
      
      preg_match_all("#<td.*?>(.+?)</td>#is", $v, $killstatmatches);
      $item = array(
        "last_day"  =>  array(
          "killed_players"    =>  $killstatmatches[1][1],
          "killed_by_players" =>  $killstatmatches[1][2]
        ),
        "last_week" =>  array(
          "killed_players"    =>  $killstatmatches[1][3],
          "killed_by_players" =>  $killstatmatches[1][4]
        )
      );
      $killstats[$killstatmatches[1][0]] = $item;
    }
    
    return $killstats;
  }
 
  /**
  * Checks if a character is a gamemaster or not
  * 
  * @param string character name
  * @return boolean GM status 
  */
  public static function isGamemaster($name)
  {
    $charinfo = self::characterInfo($name);
    return (isset($charinfo["position"]) && $charinfo["position"] == "Gamemaster");
  }
  
}
