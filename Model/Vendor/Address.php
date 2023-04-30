<?php

class Model_Vendor_Address extends Model_Core_Table
{
	public function __construct()
	{	
		parent::__construct();
		$this->setResourceClass('Model_Vendor_Address_Resource');
	}

}