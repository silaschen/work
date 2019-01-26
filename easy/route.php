<?php
namespace easy;
/**
 * 
 */
class Route
{

	static public function autoLoad($class){
		$class_path=str_replace('\\', '/', $class);

		$path_params = explode("/", $class_path);
		var_dump($class_path);
		$php_script = ucfirst(end($path_params));
		array_pop($path_params);
		$path = implode("/", $path_params);
		$class_path = sprintf("%s/%s/%s",APP_PATH,$path,$php_script.'.php');

		// var_dump($class_path);die;

		if (file_exists($class_path)) {
			// var_dump($class_path);
			include_once($class_path);
		}

	}


	static public function load(){

		$url = $_SERVER['REQUEST_URI'];
		$params = explode("/", ltrim($url,'/'));
		$moudle = $params[0];
		$controller = $params[1];
		$action=$params[2];
		$class = sprintf("app\%s\controller\%s",$moudle,ucfirst($controller));
		$Con = new $class();
		$Con->$action();
	}





















}