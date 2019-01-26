<?php
namespace easy;
/**
 * 
 */
use easy\Dispatcher;
class Load
{

	static public function autoLoad($class){
		$class_path=str_replace('\\', '/', $class);

		$path_params = explode("/", $class_path);
		// var_dump($class_path);
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


	static public function load($dispatcher){

		$url = $_SERVER['REQUEST_URI'];
		$httpMethod = $_SERVER['REQUEST_METHOD'];


		$routeInfo = $dispatcher->dispatch($httpMethod, $url);
		
		if ($routeInfo[0] == Dispatcher::FOUND) {
				$args = $routeInfo[2];

				$url = $routeInfo[1];

				if (is_string($url) && preg_match("/@/", $url)) {
					//
					$url_path = explode("/", str_replace("@", "/", $url));
					$action =  end($url_path);
					array_pop($url_path);
					$controller = end($url_path);
					array_pop($url_path);
					$class = sprintf("%s\%s",implode("\\", $url_path),ucfirst($controller));

					if ($args) {
						foreach ($args as $key => $value) {
							$_GET[$key] = $value;
						}
					}
					$controller = new $class();
					$controller->$action();



				}


		}else{



		$params = explode("/", ltrim($url,'/'));
		$moudle = $params[0];
		$controller = $params[1];
		$action=$params[2];
		$class = sprintf("app\%s\controller\%s",$moudle,ucfirst($controller));
		$Con = new $class();
		$Con->$action();







		}

	




	}






}