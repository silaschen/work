<?php
namespace easy;
/**
 * 视图base 
 */
include_once realpath(APP_PATH. "/easy/libs/smarty-3.1.29/libs/Smarty.class.php");

define("SMARTY_TEMPLATE_PATH", realpath(APP_PATH . "/smarty/templates"));
define("SMARTY_COMPILE_PATH", realpath(APP_PATH . "/smarty/compile"));
define("SMARTY_CACHE_PATH", realpath(APP_PATH . "/smarty/cache"));

class View
{
	

	static $instance;
	private $smarty;
		
	private function __construct(){

		// print_r("我被加载啦哈哈哈哈");
		$this->smarty = new \Smarty();
		$this->smarty->setTemplateDir(SMARTY_TEMPLATE_PATH);
		$this->smarty->setCacheDir(SMARTY_CACHE_PATH);
		$this->smarty->setCompileDir(SMARTY_COMPILE_PATH);
	}

	static public function createInstance(){
		if (self::$instance) {
			return self::$instance;
		}else{
			self::$instance = new self();
			return self::$instance;
		}
	} 


	static function render($file){
		$file = APP_PATH.'/app/view/'.$file;
		if (!file_exists($file)) {
			throw new \Exception("this view file does not exists", 1);
		}
		(self::createInstance())->smarty->display($file);

	}

	static function assign($attr,$value){
		
		(self::createInstance())->smarty->assign($attr,$value);
	} 

		



}