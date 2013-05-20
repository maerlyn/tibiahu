#!/usr/bin/env php
<?php

set_time_limit(0);

$app = require __DIR__ . "/app/tibiahu.php";
$app->boot();

use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;

$console = new Application("Tibiahu", "2.0");

if (is_dir($dir = ROOT . "/src/Maerlyn/Command")) {
    $finder = new Finder();

    $finder->files()->name("*Command.php")->in($dir);
    $prefix = "Maerlyn\\Command";

    foreach ($finder as $file) {
        $ns = $prefix;

        if ($relativePath = $file->getRelativePath()) {
            $ns .= '\\'.strtr($relativePath, '/', '\\');
        }

        $r = new \ReflectionClass($ns . "\\" . $file->getBasename(".php"));

        if ($r->isSubclassOf("Symfony\\Component\\Console\\Command\\Command") && !$r->isAbstract()) {
            $console->add($r->newInstance($app));
        }
    }
}

$console->run();
