<?php

use Insolis\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = require __DIR__ . "/bootstrap.php";

$app->match("/", function () use ($app) {
    $characters = $app["db.character"]->findAll();

    return $app["twig"]->render("homepage.html.twig", array("characters" => $characters));
})->bind("homepage");

//--------------------------------------------------------------------------------------------------

$app->match("/karakter/{id}", function ($id) use ($app) {
    if (!$character = $app["db.character"]->find($id)) $app->abort(404);
    $levelhistory = $app["db.levelhistory"]->findByCharacterId($id);

    return $app["twig"]->render("character.html.twig", array(
        "character"     =>  $character,
        "levelhistory"  =>  $levelhistory,
    ));
})->bind("character");

return $app;
