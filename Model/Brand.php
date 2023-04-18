<?php

class Model_Brand extends Model_Core_Table
{	

	public function __construct()
	{	
		parent::__construct();
		$this->setResourceClass('Model_Brand_Resource');
	}

}