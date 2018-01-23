#!/usr/bin/env php
<?php

require_once 'init.php';

$compiler = new \Gary\Protobuf\Compiler\Compiler();
$compiler->setGenerator(new \Gary\Protobuf\Generator\PhpMsgGenerator());
$compiler->run();
