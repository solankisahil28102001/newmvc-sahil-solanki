<?php 

class Model_Salesman_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('salesman')->setPrimaryKey('salesman_id');
	}
}