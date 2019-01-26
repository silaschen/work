<?php
namespace app\index\controller;
/**
 * 
 */
use help\Redisutil;
class Index
{
	public function __construct(){

		$this->redis=Redisutil::client();
	}
	
	public function index(){
		$redis = Redisutil::client();
	
		$redis->set('list','chensw10');
	}


	public function list(){


		print_r($this->redis->get('list'));
	}
}