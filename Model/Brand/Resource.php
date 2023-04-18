<?php 

class Model_Brand_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('brand')->setPrimaryKey('brand_id');
	}
}
