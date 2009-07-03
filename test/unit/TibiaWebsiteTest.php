<?php
  
require_once dirname(__FILE__).'/../bootstrap/unit.php';
 
$t = new lime_test(25, new lime_output_color());

$temp = TibiaWebsite::whoIsOnline("Secura");
$t->ok(is_array($temp) && count($temp),  "whoIsOnline() returns a non-empty array for an existing world");

$temp = TibiaWebsite::whoIsOnline("asdasdasd");
$t->ok(is_array($temp) && !count($temp), "whoIsOnline() returns an empty array for a non-existent world");

$temp = TibiaWebsite::characterInfo("Tele the Druid");
$t->ok(is_array($temp) && count($temp), "characterInfo() returns a non-empty array for an existing character");

$temp = TibiaWebsite::characterInfo("asdasdasdasd");
$t->ok(is_null($temp), "characterInfo() returns null for a non-existent character");

$temp = TibiaWebsite::getDeaths("Doa");
$t->ok(is_array($temp) && count($temp),  "getDeaths() returns a non-empty array for an existing character with deaths");

$temp = TibiaWebsite::getDeaths("Tele the Druid");
$t->ok(is_array($temp) && !count($temp), "getDeaths() returns an empty array for a character with no deaths");

$temp = TibiaWebsite::getDeaths("asdasdasdasd");
$t->ok(is_null($temp), "getDeaths() returns null for a non-existent character");

$temp = TibiaWebsite::lastDeath("Doa");
$t->ok(is_array($temp) && count($temp), "lastDeath() returns an array with one element for an existing character with deaths");

$temp = TibiaWebsite::lastDeath("Tele the Druid");
$t->ok(is_array($temp) && !count($temp), "lastDeath() returns an empty array for a character with no deaths");

$temp = TibiaWebsite::lastDeath("asdasdasd");
$t->ok(is_null($temp), "lastDeath() returns null for a non-existent character");
//10

$t->ok( TibiaWebsite::characterExists("Tele the Druid"), "characterExists() returns true for an existing character");
$t->ok(!TibiaWebsite::characterExists("asdasdasd"), "characterExists() returns false for a nonexistents character");

$temp = TibiaWebsite::getGuildList("Secura");
$t->ok(is_array($temp) && count($temp), "getGuildList() returns a nonempty array for an existing world");

$temp = TibiaWebsite::getGuildList("asdasd");
$t->ok(is_array($temp) && !count($temp), "getGuildList() returns an empty array for a nonexistent world");

$temp = TibiaWebsite::getGuildMembers("Pannon Guardians");
$t->ok(is_array($temp) && count($temp), "getGuildMembers() returns a nonempty array for an existing guild");

$temp = TibiaWebsite::getGuildMembers("asdasdasdasd");
$t->ok(is_array($temp) && !count($temp), "getGuildMembers() returns an empty array for a nonexistent guild");

$t->ok( TibiaWebsite::verifyCode("Tele the Druid", "tibiahu-levelup-4bd6"), "verifyCode() returns true for a valid code");
$t->ok(!TibiaWebsite::verifyCode("Tele the Druid", "asdasdasd"), "verifyCode() returns false for an invalid code");

$temp = TibiaWebsite::getListOfCreatures();
$t->ok(is_array($temp) && count($temp), "getListOfCreatures() returns a nonempty array");

$temp = TibiaWebsite::getWorlds();
$t->ok(is_array($temp) && count($temp), "getWorlds() returns a nonempty array");
//20

$temp = TibiaWebsite::getNewsticker();
$t->ok(is_array($temp) && 5 == count($temp), "getNewsTicker() returns an array with 5 elements");

$temp = TibiaWebsite::getLatestNews();
$t->ok(is_array($temp) && 5 == count($temp), "getLatestNews() returns an array with 5 elements");

$temp = TibiaWebsite::getFeaturedArticle();
$t->ok(is_array($temp) && 3 == count($temp), "getFeaturedArticle() returns an array with 3 elements");

$temp = TibiaWebsite::getActivePolls();
$t->ok(is_array($temp), "getActivePolss() returns an array");

$temp = TibiaWebsite::getKillStatistics("Secura");
$t->ok(is_array($temp) && count($temp), "getKillStatistics() returns a nonempty array");
//25
