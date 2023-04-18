<?php 

class Model_EntityType extends Model_Core_Table
{
	public function __construct()
	{	
		parent::__construct();
		$this->setResourceClass('Model_EntityType_Resource');
	}

}