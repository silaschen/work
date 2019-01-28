<?php
namespace easy;
/**
 * framework entrance
 *自动加载
 *分发路由
 */
require __DIR__.'/Load.php';
use route\Route;

class Easy
{
	static protected $instanceDispatcher;


	static public  function run(){

		self::autoLoad();
		self::registerRoute();
		Load::Init(self::$instanceDispatcher);

	}


	static public function autoLoad(){

		spl_autoload_register(array('\easy\Load','autoLoad'));
	}


	static public function registerRoute(){
		self::$instanceDispatcher = Route::load();
	}




}
