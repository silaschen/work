<?php
namespace easy;

/**
 * 
 */
class PgModel extends \easy\db\postgres
{
	
	function __construct()
	{
		parent::__construct();
	}


	public function Findone(){
		$this->getDataCount('a');
	}



}