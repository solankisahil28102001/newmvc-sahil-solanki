<?php 

class Model_Category_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('category')->setPrimaryKey('category_id');
	}
}