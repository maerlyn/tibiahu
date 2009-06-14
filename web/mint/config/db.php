<?php
/******************************************************************************
 Mint
  
 Copyright 2004-2008 Shaun Inman. This code cannot be redistributed without
 permission from http://www.shauninman.com/
 
 More info at: http://www.haveamint.com/
 
 ******************************************************************************
 Configuration
 ******************************************************************************/
 if (!defined('MINT')) { header('Location:/'); } // Prevent viewing this file 

$Mint = new Mint (array
(
	'server'	=> 'localhost',
	'username'	=> 'tibia',
	'password'	=> '2MshQn39cAyEaHPr',
	'database'	=> 'tibia',
	'tblPrefix'	=> 'mint_'
));