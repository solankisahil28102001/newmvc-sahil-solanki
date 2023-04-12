<?php

class Model_Customer_Address extends Model_Core_Table
{
	public function __construct()
	{	
		parent::__construct();
		$this->setResourceClass('Model_Customer_Address_Resource');
	}

}