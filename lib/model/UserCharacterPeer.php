<?php

require 'lib/model/om/BaseUserCharacterPeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'tibia_user_character' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.4.1 on:
 *
 * Sat May  8 15:56:59 2010
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class UserCharacterPeer extends BaseUserCharacterPeer {

  public static function retrieveForUserId($user_id)
  {
    $c = new Criteria();
    $c->add(self::USER_ID, $user_id);
    return self::doSelectJoinCharacter($c);
  }

} // UserCharacterPeer
