<?php
namespace easy;
/**
 * 
 */
class Config
{
	static $box;
	
	static function get($name,$file){
		$file_path = APP_PATH.'/config/'.$file.'.php';
		if (self::$box) {
			return self::$box[$name];
		}
		if (file_exists($file_path)) {
			$config = require_once $file_path;
			self::$box = $config;
			return self::$box[$name];

		}else{
			throw new \Exception("config file does not exist", 1);
			
		}




	}



}