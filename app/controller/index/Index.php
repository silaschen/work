<?php
namespace app\controller\index;

use help\Redisutil;
use easy\View;
class Index
{
	public function index(){
		View::render('index.tpl');
	}

}