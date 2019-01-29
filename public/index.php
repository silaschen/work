<?php
/**
 * the only entrance of this framework
 */
namespace easy;

define("APP_PATH", __DIR__."/..");
include_once APP_PATH.'/easy/easy.php';
include_once APP_PATH.'/vendor/autoload.php';
include_once APP_PATH.'/easy/common.php';

Easy::Run();