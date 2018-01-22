<?php
/**
 * Created by PhpStorm.
 * User: liugang
 * Date: 2018/1/5
 * Time: 下午6:03
 */

$dir = dirname(__DIR__);
exec("cd $dir && rm -rf ./tests/build; mkdir -p ./tests/build; ./protoc-gen.php --out=tests/build --grpc_out=tests/build tests/*.proto");
$loader = require (__DIR__ . "/../vendor/autoload.php");
$loader->addPsr4("Gary\\Test\\", "$dir/tests/build/Gary/Test");
$loader->addPsr4("GPBMetadata\\Tests\\", "$dir/tests/build/GPBMetadata/Tests");