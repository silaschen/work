<?php
namespace easy;
/**
 * framework entrance
 *自动加载
 *分发路由
 */
include_once __DIR__.'/Load.php';

use route\Route;

class Easy
{
	static protected $instanceDispatcher;


	static public  function run(){
		self::autoLoad();
		self::ErrorHandler();
		self::registerRoute();
		Load::Init();

	}

	static function  ErrorHandler(){
		if (\easy\Config::get('DEBUGE','app') == true) {
			ini_set("display_errors", 'On');
			error_reporting(E_ALL);
			$whoops = new \Whoops\Run;
			$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
			$whoops->register();
		}else{
			ini_set("display_errors", 'Off');
		}
		
	}


	static public function autoLoad(){

		spl_autoload_register(array('\easy\Load','autoLoad'));
	}


	static public function registerRoute(){
		// self::$instanceDispatcher = Route::load();
		include_once APP_PATH.'/route/Route.php';
	}




}
