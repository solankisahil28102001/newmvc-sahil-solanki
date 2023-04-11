<?php 

class Model_Eav_Attribute_Resource extends Model_Core_Table_Resource
{	
	function __construct()
	{
		parent::__construct();
		$this->setTableName('eav_attribute')->setPrimaryKey('attribute_id');
	}
}