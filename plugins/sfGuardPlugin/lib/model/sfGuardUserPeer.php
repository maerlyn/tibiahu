<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardUserPeer.php 7634 2008-02-27 18:01:40Z fabien $
 */
class sfGuardUserPeer extends PluginsfGuardUserPeer
{

  public static function retrieveForVerify($email)
  {
    $c = new Criteria();
    $c->add(self::PASSWORD, $email);
    $c->add(self::IS_ACTIVE, false);
    return self::doSelectOne($c);
  }

}
