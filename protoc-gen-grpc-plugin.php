#!/usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';

$compiler = new \Gary\Protobuf\Compiler\Compiler();
$compiler->setGenerator(new \Gary\Protobuf\Generator\PhpGrpcGenerator());
$compiler->runAsPlugin();