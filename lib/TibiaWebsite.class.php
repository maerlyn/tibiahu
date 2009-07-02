<?php

/**
* Accessor functions for the Tibia webstie
* 
* @package tibiahu
* @author Maerlyn <maerlyng@gmail.com>  
*/
abstract class TibiaWebsite
{
  
  /**
  * Gets the data of the currently online characters
  * 
  * @param string name of the world
  * @return array characters with name, level and vocation
  */
  public static function whoIsOnline($world = "Secura")
  {
    $world = ucfirst($world);
    $website = RemoteFile::get("http://www.tibia.com/community/?subtopic=whoisonline&world={$world}");
    
    if (false !== stripos($website, "403 forbidden")) {
      set_time_limit(20);
      sleep(10);
      $website = RemoteFile::get("http://www.tibia.com/community/?subtopic=whoisonline&world={$world}");
      if (false !== stripos($website, "403 forbidden")) {
        return array();
      }
    }    
    
    preg_match_all("@<tr bgcolor=.+?>(.+?)<.tr>@is", $website, $matches);
        
    $chars = array();
    foreach ($matches[0] as $v) {
      set_time_limit(5);
      $char = array();
      $name = preg_replace("#.+?subtopic=characters&name=(.+?)\">.+#is", "\\1", $v);
      $name = urldecode($name);
      $char["name"] = $name;
      
      if ($name[0] == "<") {
        continue;
      }
      
      $level = preg_replace("#.+?<td width=10%>(\d+?)</td>.+#is", "\\1", $v);
      $char["level"] = $level;
      
      $voc = preg_replace("#.+?<td width=20%>(.+?)</td>.+#is", "\\1", $v);
      $char["vocation"] = $voc;
      
      $chars[] = $char;
    }

    return $chars;
  }
  
  /**
  * Gets the character information for a character
  * 
  * @param string name of the character
  * @return array the characters data, empty array if tibia.coms down, null if the char does not exist
  */
  public static function characterInfo($charname)
  {
    $website = RemoteFile::get("http://www.tibia.com/community/?subtopic=character&name=" . urlencode($charname));
    if (stripos($website, "</B> does not exist.</TD>")) {
      return null;
    }

    if (false !== stripos($website, "403 forbidden")) {
      return "403";
    }
    
    $character = array();

    preg_match(
      "@<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR=#505050><TD COLSPAN=2 CLASS=white><B>Character Information</B>.+?</TABLE>@is",
      $website, 
      $matches
      );
    $characterinfo = $matches[0];
   
    
    //<TD WIDTH=20%>Name:</TD><TD>Tele the Druid</TD></TR>
    //<TD WIDTH=20%>Name:</TD><TD>Shaya Liron, will be deleted at Mar&#160;12&#160;2009,&#160;03:58:25&#160;CET</TD></TR>
    preg_match("@<TD WIDTH=20%>Name:</TD><TD>(.+?)</TD></TR>@is", $characterinfo, $matches);
    $character["name"] = $matches[1];
    
    if (stripos($character["name"], ", will be deleted at")) {
      $tmp = explode(", will be deleted at", $character["name"]);
      $character["name"] = $tmp[0];
      $character["deleted"] = strtotime(str_replace("&#160;", " ", $tmp[1]));
    }
    
    //<TD>Sex:</TD><TD>male</TD>
    preg_match("@<TD>Sex:</TD><TD>((?:fe|)male)</TD>@is", $characterinfo, $matches);
    $character["sex"] = $matches[1];
    
    //<TD>Profession:</TD><TD>Elder Druid</TD></TR>
    preg_match("@<TD>Profession:</TD><TD>(.+?)</TD></TR>@is", $characterinfo, $matches);
    $character["profession"] = $matches[1];
    
    //<TD>Level:</TD><TD>68</TD></TR>
    preg_match("@<TD>Level:</TD><TD>(\\d+)</TD></TR>@is", $characterinfo, $matches);
    $character["level"] = $matches[1];
    
    //<TD>World:</TD><TD>Secura</TD></TR>
    preg_match("@<TD>World:</TD><TD>(.+?)</TD></TR>@is", $characterinfo, $matches);
    $character["world"] = $matches[1];
    
    //<TD>Residence:</TD><TD>Kazordoon</TD>
    preg_match("@<TD>Residence:</TD><TD>(.+?)</TD>@is", $characterinfo, $matches);
    $character["residence"] = $matches[1];
    
    //<TR BGCOLOR=#F1E0C6><TD>Married to:</TD><TD><A HREF="http://www.tibia.com/community/?subtopic=characters&name=Maci+Laci+The+Druid">Maci&#160;Laci&#160;The&#160;Druid</A></TD></TR>
    if (preg_match("@<td>Married to:</td><td><a href=.+?>(.+?)</a></td>@is", $characterinfo, $matches)) {
      $character["married_to"] = str_replace("&#160;", " ", $matches[1]);
    }
    
    //<TD>Guild&#160;membership:</TD><TD>Friend of the nature of the <A HREF="<url>">Pannon&#160;Guardians</A></TD></TR>
    if (preg_match("@<TD>Guild&#160;membership:</TD><TD>(.+?) of the <A HREF.+?>(.+?)</A></TD></TR>@is", $characterinfo, $matches)) 
    {
      $character["guild"] = array(
        "name"  =>  str_replace("&#160;", " ", $matches[2]),
        "rank"  =>  str_replace("&#160;", " ", $matches[1])
      );
    }
    
    //<TD>Last login:</TD><TD>Mar&#160;01&#160;2009,&#160;18:54:54&#160;CET</TD></TR>
    preg_match("@<TD>Last login:</TD><TD>(.+?)</TD></TR>@is", $characterinfo, $matches);    
    $character["lastlogin"] = strtotime(str_replace("&#160;", " ", $matches[1]));
    
    //<TD>Account&#160;Status:</TD><TD>Premium Account</TD></TR>
    preg_match("@<TD>Account&#160;Status:</TD><TD>(Premium|Free) Account</TD></TR>@is", $characterinfo, $matches);
    $character["accountstatus"] = $matches[1];
    
    //<TD>House:</TD><TD>Nobility Quarter 2 (Kazordoon) is paid until Mar&#160;05&#160;2009</TD></TR>
    if (preg_match("@<TD>House:</TD><TD>(.+?\\)) is paid until .+?</TD></TR>@is", $characterinfo, $matches)) {
        $character["house"] = $matches[1];
    }
    
    // --- --- --- 
    
    //<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR=#505050><TD COLSPAN=2 CLASS=white><B>Character Deaths</B></TD></TR><TR BGCOLOR=#F1E0C6><TD WIDTH=25%>Feb&#160;25&#160;2009,&#160;08:09:29&#160;CET</TD><TD>Died at Level 44 by an orc berserker</TD></TR></TABLE>
    if (preg_match("@<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR=#505050><TD COLSPAN=2 CLASS=white><B>Character Deaths</B></TD>.+?</TABLE>@is", $website, $matches)) 
    {
      $deathlist = $matches[0];
      $character["deaths"] = array();
      
      //<TR BGCOLOR=#F1E0C6><TD WIDTH=25%>Feb&#160;25&#160;2009,&#160;08:09:29&#160;CET</TD><TD>Died at Level 44 by an orc berserker</TD></TR>
      preg_match_all("@<TR BGCOLOR=#.{6}><TD W.+?>(.+?)</TD><TD>Died at Level \\d+ by a(.+?)</TD></TR>@is", $deathlist, $matches);
      foreach ($matches[0] as $key => $value) {
        $character["deaths"][] = array(
          "time"    =>  strtotime(str_replace("&#160;", " ", $matches[1][$key])),
          "reason"  =>  ($matches[2][$key][0] != "n" ? $matches[2][$key] : substr($matches[2][$key], 2))
        );
      }
    }
    
    //<TD WIDTH=20% VALIGN=top CLASS=red>Banished:</TD><TD CLASS=red>until Mar&#160;04&#160;2009,&#160;15:52:25&#160;CET because of hacking</TD></TR>
    //<TD WIDTH=20% VALIGN=top CLASS=red>Banished:</TD><TD CLASS=red>permanently because of invalid payment</TD></TR>
    if (strpos($website, " CLASS=red>Banished:")) {
        preg_match("@<TD WIDTH=20% VALIGN=top CLASS=red>Banished:</TD><TD CLASS=red>(.+?) because of (.+?)</TD></TR>@is", $website, $matches);
        if (false !== strpos($matches[1], "until")) {
            if (false !== strpos($matches[1], "deletion")) {
                $until = 0; //nullas unix timestamp
            } else {
              $until = strtotime(str_replace("&#160;", " ", substr($matches[1], 6)));
            }
        } else {
          $until = $matches[1];
        }
        $character["banishment"] = array(
          "until"   =>  $until,
          "reason"  =>  $matches[2]
        );
    }
    
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
    $website = RemoteFile::get("http://www.tibia.com/community/?subtopic=character&name=" . urlencode($charname));
    if (stripos($website, "</B> does not exist.</TD>")) {
      return null;
    }
    
    $death = array();
    
    if (preg_match("@<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR=#505050><TD COLSPAN=2 CLASS=white><B>Character Deaths</B></TD>.+?</TABLE>@is", $website, $matches)) 
    {
      $deathlist = $matches[0];
      
      //<TR BGCOLOR=#F1E0C6><TD WIDTH=25%>Feb&#160;25&#160;2009,&#160;08:09:29&#160;CET</TD><TD>Died at Level 44 by an orc berserker</TD></TR>
      preg_match("@<TR BGCOLOR=#.{6}><TD W.+?>(.+?)</TD><TD>Died at Level (\\d+) by (.+?)</TD></TR>@is", $deathlist, $matches);
      $death = array(                                                               
        "time"    =>  strtotime(str_replace("&#160;", " ", $matches[1])),
        "level"   =>  $matches[2],
        "reason"  =>  trim(preg_replace('#^(an |a )(.+?)$#i', "\\2", $matches[3]))
      );
    }
    
    return $death;
  }
  
  /**
  * Gets the death list for a given character
  * 
  * @param string name of character
  * @return array the death data (time, reason), empty array if tibia.com is down or theres no death, null if char doesnt exist
  */
  public static function getDeaths($charname)
  {
    $website = RemoteFile::get("http://www.tibia.com/community/?subtopic=character&name=" . urlencode($charname));
    if (false !== stripos($website, "</B> does not exist.</TD>")) {
      return null;
    }
    if (false !== stripos($website, "403 forbidden")) {
      return "403";
    }
    
    $deaths = array();
    
    if (preg_match("@<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR=#505050><TD COLSPAN=2 CLASS=white><B>Character Deaths</B></TD>.+?</TABLE>@is", $website, $matches)) 
    {
      $deathlist = $matches[0];
      
      //<TR BGCOLOR=#F1E0C6><TD WIDTH=25%>Feb&#160;25&#160;2009,&#160;08:09:29&#160;CET</TD><TD>Died at Level 44 by an orc berserker</TD></TR>
      preg_match_all("@<TR BGCOLOR=#.{6}><TD W.+?>(.+?)</TD><TD>Died at Level (\\d+) by (.+?)</TD></TR>@is", $deathlist, $matches);
        foreach($matches[0] as $k => $v) {
        $deaths[] = array(
          "time"    =>  strtotime(str_replace("&#160;", " ", $matches[1][$k])),
          "level"   =>  $matches[2][$k],
          "reason"  =>  trim(preg_replace('#^(an |a )(.+?)$#i', "\\2", $matches[3][$k]))
        );
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
    $website = RemoteFile::get("http://www.tibia.com/community/?subtopic=character&name=" . urlencode($charname));
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
    $website = RemoteFile::get("http://www.tibia.com/community/?subtopic=guilds&world=" . urlencode($world));
    
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
    $website = RemoteFile::get("http://www.tibia.com/community/?subtopic=guilds&page=view&GuildName=" . urlencode($guild));
    if (false !== stripos($website, "Internal error")) {
      return array();
    }
    
    preg_match_all("#<table border=0 cellspacing=1.+?<b>Guild Members</b></td></tr>(.+?)</table>#is", $website, $matches);
    preg_match_all("#<a href=\"http://www.tibia.com.+?subtopic=characters.+?\">(.+?)</a>#is", $matches[1][0], $matches);
    
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
    $website = RemoteFile::get("http://www.tibia.com/community/?subtopic=character&name=" . urlencode($character));
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
    $website = RemoteFile::get("http://tibia.wikia.com/wiki/List_of_Creatures");
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
    $website = RemoteFile::get("http://www.tibia.com/news/?subtopic=latestnews");
    
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
    $website = RemoteFile::get("http://www.tibia.com/news/?subtopic=latestnews");
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
        "body"  =>  self::articleCleanup(preg_replace("#.+?<tr>[\n ]+?<td.+?>(.+?)</td>[\n ]+?<tr><td><div.*#is", "\\1", $v))
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
    $website = RemoteFile::get("http://www.tibia.com/news/?subtopic=latestnews");
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
    $website = RemoteFile::get("http://www.tibia.com/community/?subtopic=polls");
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
    $website = RemoteFile::get("http://www.tibia.com/community/?subtopic=killstatistics&world=" . ucfirst($world));
    
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
  
}