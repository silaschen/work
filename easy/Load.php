<?php
namespace easy;
/**
 * 自动加载
 * 路由分发
 */
use easy\Routehand;
class Load
{

	static public function autoLoad($class){
		$class_path=str_replace('\\', '/', $class);
		$path_params = explode("/", $class_path);
		$php_script = ucfirst(end($path_params));
		array_pop($path_params);
		$path = implode("/", $path_params);
		$class_path = sprintf("%s/%s/%s",APP_PATH,$path,$php_script.'.php');

		if (file_exists($class_path)) {
			// var_dump($class_path);
			include_once($class_path);
		}

	}



	static public function Init(){

		$url = $_SERVER['REQUEST_URI'];
		if (preg_match("/\?/", $url)) {
			$url = substr($url,0,strpos($url, '?'));
		}

		$httpMethod = $_SERVER['REQUEST_METHOD'];
		// var_dump($_SERVER);die;
		$routeInfo = Routehand::dispatch($httpMethod, $url);

		if ($routeInfo && $routeInfo[0] == Routehand::FOUND) {
				$args = $routeInfo[2];
				$url = $routeInfo[1];

				if (is_string($url) && preg_match("/@/", $url)) {
					$url_path = explode("/", str_replace("@", "/", $url));
					if (count($url_path) < 3) {
						throw new \Exception("Error route defined", 1);
					}
					$action =  end($url_path);
					array_pop($url_path);
					$controller = end($url_path);
					array_pop($url_path);
					$class = sprintf("app\controller\%s\%s",implode("\\", $url_path),ucfirst($controller));
					$controller = new $class();
					$controller->$action($args);
				}
		}else{


					$params = explode("/", ltrim($url,'/'));
					
					if ($url == '/') {
						$moudle = \easy\Config::get('DEFAULT_MOUDLE','app');
						$controller = \easy\Config::get('DEFAULT_CONTROLLER','app');
						$action=\easy\Config::get('DEFAULT_ACTION','app');
					}elseif (count($params)<3) {
						throw new \Exception("Bad request", 1);
					}else{
					
							$moudle = $params[0];
							$controller = $params[1];
							$action=$params[2];
					}

					$class = sprintf("app\controller\%s\%s",$moudle,ucfirst($controller));
					$Con = new $class();
					$Con->$action();

		}

	



	}






}