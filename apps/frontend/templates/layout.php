<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $sf_user->getCulture() ?>">
<head>
  <title>
    <?php if (!include_slot("title")): ?>
      <?php echo __("Magyar Tibia rajongói oldal") ?>
    <?php else: ?>
      # <?php echo __("Magyar Tibia rajongói oldal") ?>
    <?php endif; ?>
  </title>
  <?php include_http_metas() ?>
  <?php include_metas() ?>
  <?php include_stylesheets() ?>
  <link rel="shortcut icon" href="<?php echo image_path("favicon.ico") ?>" />
  <link rel="alternate" type="application/atom+xml" title="<?php echo __("Hírek feed", null, "feed") ?>" href="<?php echo url_for("@news_feed") ?>" />
</head>

<body>

<div id="container">

  <div id="header">
    <div class="center"><a href="<?php echo url_for("@homepage") ?>"><img src="<?php echo image_path("tibia_logo.jpg") ?>" alt="<?php echo __("Az oldal logoja") ?>" /></a></div>
  </div>

  <div id="middle">

    <div id="leftbox">
      <div class="containerbox">
        <h3><?php echo __("Menü") ?></h3>
        <div class="panel">
          <ul>
            <li><?php echo link_to(__("Hírek", null, "news"), "@news_index") ?></li>
            <li><?php echo link_to(__("Cikkek", null, "article"), "@article_index") ?></li>
            <li><?php echo link_to(__("Részletes keresés"), "@character_advancedsearch") ?></li>
            <li><?php echo link_to(__("Guildek"), "@guild_index") ?></li>
            <li><?php echo link_to(__("Botterek"), "@character_botters") ?></li>
            <li><?php echo link_to(__("Hackerek"), "@character_hackers") ?></li>
            <li><?php echo link_to(__("Tradelt/megosztott karakterek"), "@character_acctraders") ?></li>
            <li><?php echo link_to(__("GM-kereső", null, "gamemaster"), "@gmfinder_index") ?></li>
          </ul>
        </div>
      </div>  <!-- /containerbox -->
      
      <div class="containerbox">
        <h3><?php echo __("Gyorskeresés") ?></h3>
        <div class="panel">
          <?php include_component("general", "quicksearch") ?>
        </div>
      </div> <!-- /containerbox -->
      
      <div class="containerbox">
        <h3><?php echo __("Kalkulátorok") ?></h3>
        <div class="panel">
          <ul>
            <li><?php echo link_to(__("Szintlépés"), "@calculator_level") ?></li>
            <li><?php echo link_to("Magic level", "@calculator_mlvl") ?></li>
            <li><?php echo link_to("Stamina", "@calculator_stamina") ?></li>
            <li><?php echo link_to(__("Blessing árak", array(), "calculators"), "@calculator_blessing") ?></li>
            <li><?php echo link_to("Soul", "@calculator_soul") ?></li>
            <li><?php echo link_to(__("Chatlog statisztika", null, "calculators"), "@calculator_chatlog") ?></li>
          </ul>
        </div>
      </div> <!-- /containerbox -->
      
      <div class="containerbox">
        <div class="panel">
          <?php include_component("general", "language") ?>
        </div>
      </div> <!-- /containerbox -->

      <div style="width: 122px; margin: auto;">
        <script type="text/javascript">
          google_ad_client = "pub-5937469762999819";
          google_ad_slot = "0814251540";
          google_ad_width = 120;
          google_ad_height = 600;
        </script>
        <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
      </div>
      
    </div> <!-- /leftbox -->

    <div id="rightbox">

<?php echo $sf_content ?>

    </div> <!-- /rightbox -->
  </div> <!-- /middle -->

  <div id="footer">
    <div id="footerbox" class="center">
      :: <?php echo __("Copyright &copy; 2009 Tibia.hu, minden jog fenntartva") ?> :: <?php echo link_to(__("Kapcsolat"), "@contact") ?> ::<br />
      :: <?php echo __("Ez egy rajongói oldal, a hivatalos honlap: <a href=\"http://tibia.com/\">tibia.com</a>") ?> ::<br />
      :: <?php echo __("Szerveridő: ");use_helper("Date");echo format_datetime(time()) ?> :: 
      <a href="<?php echo url_for("@last_update") ?>"><?php echo __("Utolsó frissítés %minutes% perce", array("%minutes%" => floor((time()-CronLogPeer::getTimeOfLastUpdate())/60))) ?></a> ::
    </div>
  </div> <!-- /footer -->

</div> <!-- /container -->

<script src="http://www.google-analytics.com/ga.js" type="text/javascript"></script>
<script src="http://tibia.hu/mint/index.php?js" type="text/javascript"></script>
<?php include_javascripts() ?>
</body>
</html>