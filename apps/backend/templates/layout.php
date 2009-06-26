<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>Tibia.hu - Admin</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php use_stylesheet('admin.css') ?>
    <?php include_javascripts() ?>
    <?php include_stylesheets() ?>
  </head>
  <body>
    <div id="container">
      <div id="header">
        <h1>
          Tibia.hu admin
        </h1>
      </div>
      <?php if ($sf_user->isAuthenticated()) : ?>
      <div id="menu">
        <ul>
          <li><?php echo link_to('News', '@news') ?></li>
          <li><?php echo link_to('News categories', '@news_category') ?></li>
          <li><?php echo link_to('Users', '@sf_guard_user') ?></li>
          <li><?php echo link_to('Logout', '@sf_guard_signout') ?></li>
        </ul>
      </div>
      <?php endif; ?>
      <div id="content">
        <?php echo $sf_content ?>
      </div>
 
      <div id="footer">
      </div>
    </div>
  </body>
</html>