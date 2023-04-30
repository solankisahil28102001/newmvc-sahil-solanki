<?php 

class Model_Salesman_Address_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('salesman_address')->setPrimaryKey('address_id');
	}
}

