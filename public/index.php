<?php
/**
 * the only entrance of this framework
 */
namespace easy;
ini_set("display_errors", 'On');
error_reporting(E_ALL);
define("APP_PATH", __DIR__."/..");
require __DIR__.'/../easy/easy.php';

(new Easy())->Run();