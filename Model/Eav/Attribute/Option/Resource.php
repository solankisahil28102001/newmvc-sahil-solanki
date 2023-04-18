<?php 

class Model_Eav_Attribute_Option_Resource extends Model_Core_Table_Resource
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTableName('eav_attribute_option')->setPrimaryKey('option_id');
	}
}