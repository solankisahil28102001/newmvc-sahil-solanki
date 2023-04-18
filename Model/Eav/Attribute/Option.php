<?php 

class Model_Eav_Attribute_Option extends Model_Core_Table
{
	function __construct()
	{
		parent::__construct();
		$this->setResourceClass('Model_Eav_Attribute_Option_Resource');
		$this->setCollectionClass('Model_Eav_Attribute_Option_Collection');
	}
}