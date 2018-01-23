<?php
/**
 * Created by PhpStorm.
 * User: liugang
 * Date: 2018/1/23
 * Time: 下午2:21
 */
$file = __DIR__ . '/vendor/autoload.php';
if (file_exists($file)) {
    require_once $file;
} else if (file_exists($file = __DIR__ . "/../../autoload.php")) {
    require_once $file;
} else {
    throw new \RuntimeException("cannot find autoload.php");
}

define('PROJECT_ROOT', __DIR__);