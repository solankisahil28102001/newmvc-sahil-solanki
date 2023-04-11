<?php 

class Model_Salesman_Price_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('salesman_price')->setPrimaryKey('entity_id');
	}
}