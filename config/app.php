<?php
return [
	'DEBUGE'=>true,
	'pgsql'=>array(
	    "host" => "127.0.0.1",
	    "port" => 5432,
	    "database" => "xxx",
	    "username" => "root",
	    "password" => "root",
	),

	'redis'=>array(
		'host'=>'127.0.0.1',
		'db'=>1,
		'port'=>6379
	),

	'DEFAULT_MOUDLE'=>'index',
	'DEFAULT_CONTROLLER'=>'Index',
	'DEFAULT_ACTION'=>'index',

];
