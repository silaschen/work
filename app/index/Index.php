<?php
namespace app\index;
/**
 * 
 */
use help\Redisutil;
use easy\db\postgres;
class Index
{
	public function __construct(){

		$this->redis=Redisutil::client();
	}
	
	public function index(){
		$redis = Redisutil::client();
	
		$redis->set('list','chensw10');

		$model = new \app\model\codata();
		$model->getdata();
	
	}


	public function list(){


		print_r($this->redis->get('list'));
	}
}