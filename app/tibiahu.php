<?php

use Insolis\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = require __DIR__ . "/bootstrap.php";

$app->match("/", function () use ($app) {
    $characters = $app["db.character"]->findAll();

    return $app["twig"]->render("homepage.html.twig", array("characters" => $characters));
})->bind("homepage");

return $app;
