<?php
namespace app\controller\index;

use help\Redisutil;
use easy\View;
class Index
{
	public function index(){
		$model = new \app\model\Index();
		list($page,$list) = $model->getLesson();

		View::render('index.tpl',['data'=>$list,'page'=>$page]);
		
	}

	public function test($args){

		var_dump($args);

	}

	public function list($data){

		var_dump(\easy\db\DB::table('user_alerts')->orderby("id asc")->limit(29)->get());
	}

}