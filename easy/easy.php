<?php
namespace easy;
/**
 * framework entrance
 */
require __DIR__.'/Load.php';
class Easy
{
	
	function __construct()
	{
		spl_autoload_register(array('\easy\Load','autoLoad'));
	}

	public  function run(){
		$dis = \config\route::Load();
		Load::Load($dis);


	}





}
