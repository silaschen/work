<?php
namespace config;
/**
 * 
 */
use easy\Dispatcher;
class Route
{
	


   static public function Load(){
   		echo 'load route config....';
   		$dis = new Dispatcher();

   		$dis->get('/index',"app@index@controller@Index@index");

   		$dis->get('/app',"app@index@controller@Index@list");

   		$dis->get('/get/{id}/{appid}',"app@api@controller@Meeting@index");


   		return $dis;


   } 
	



}