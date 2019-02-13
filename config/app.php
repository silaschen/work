<?php
return [
	'DEBUGE'=>true,
	'pgsql'=>array(
		"driver"=>"pgsql",
		"host" => "10.120.23.175",
	    "port" => 5432,
	    "database" => "senseusers",
	    "username" => "sense",
	    "password" => "QXENu6jKyaCG",
	    'schema'   => 'public',
	),

	'redis'=>array(
		'host'=>'127.0.0.1',
		'db'=>1,
		'port'=>6379
	),

	'DEFAULT_MOUDLE'=>'index',
	'DEFAULT_CONTROLLER'=>'Index',
	'DEFAULT_ACTION'=>'index',
	'pagesize'=>6,

];
