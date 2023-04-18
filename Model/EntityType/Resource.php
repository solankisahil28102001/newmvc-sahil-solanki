<?php 

class Model_EntityType_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('entity_type')->setPrimaryKey('entity_type_id');
	}
}
