<?php
namespace easy;
/**
 * framework entrance
 */
require __DIR__.'/route.php';
class Easy
{
	
	function __construct()
	{
		spl_autoload_register(array('\easy\Route','autoLoad'));
	}

	public  function run(){
		Route::Load();	

	}





}
