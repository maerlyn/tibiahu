<?php

use Insolis\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = require __DIR__ . "/bootstrap.php";

$app->match("/", function () use ($app) {
    return $app["twig"]->render("homepage.html.twig");
})->bind("homepage");

return $app;
