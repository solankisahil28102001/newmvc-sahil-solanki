<?php 

class Model_Item_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('item')->setPrimaryKey('entity_id');
	}
}
