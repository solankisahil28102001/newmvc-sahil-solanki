<?php

class Model_Quote_Item extends Model_Core_Table
{

	public function __construct()
	{	
		parent::__construct();
		$this->setResourceClass('Model_Quote_Item_Resource');
	}

}