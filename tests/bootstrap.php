<?php
/**
 * Created by PhpStorm.
 * User: liugang
 * Date: 2018/1/5
 * Time: 下午6:03
 */

$dir = dirname(__DIR__);
exec("cd $dir && mkdir -p ./tests/build; ./protoc-gen-php.php -o tests/build tests/test3.proto");
$loader = require (__DIR__ . "/../vendor/autoload.php");
$loader->addPsr4("Gary\\Test\\", "$dir/tests/build/Gary/Test");
$loader->addPsr4("GPBMetadata\\Tests\\", "$dir/tests/build/GPBMetadata/Tests");