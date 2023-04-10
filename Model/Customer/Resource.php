<?php 

class Model_Customer_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('customer')->setPrimaryKey('customer_id');
	}
}

