<?php
namespace config;

use easy\Dispatcher;
class Route
{
	


   static public function Load(){
   	
   		$dis = new Dispatcher();
   		$dis->get('/index',"app@index@Index@index");
   		$dis->get('/app',"app@index@Index@list");
   		$dis->get('/get/{id}',"app@api@Meeting@index");
   		return $dis;
   } 
	



}