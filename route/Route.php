<?php
namespace route;

use easy\Dispatcher;
class Route
{
	


   static public function Load(){
   	
   		$dis = new Dispatcher();

   		$dis->get('/index',"app@controller@index@Index@index");
         
   		$dis->get('/get/{id}',"app@controller@index@Index@test");


   		return $dis;
   } 
	



}