#!/usr/bin/env php
<?php

$composerAutoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($composerAutoload)) {
    echo('The dependencies are not installed. Run "composer install" in order to install them.');
    exit(1);
}

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require_once $composerAutoload;
$loader->addPsr4("Gary\\Test\\", "./build/Gary/Test");
$loader->addPsr4("GPBMetadata\\Tests\\", "./build/GPBMetadata/Tests");

use Gary\Protobuf\Generator\Compiler;

$compiler = new Compiler();
$compiler->run(__FILE__);
