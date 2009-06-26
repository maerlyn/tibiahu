<?php

/**
 * News form.
 *
 * @package    tibiahu
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class NewsForm extends BaseNewsForm
{
  public function configure()
  {
    $this->embedI18n(array('en', 'hu'));
    $this->widgetSchema->setLabel('en', 'English');
    $this->widgetSchema->setLabel('hu', 'Hungarian');
  }
}
