<?php
namespace app\controller\index;

use help\Redisutil;
use easy\View;
class Index
{
	public function index(){
		View::render('index.tpl');
	}

	public function test($args){

		var_dump($args);

	}

	public function list($data){

		var_dump($data);
	}

}