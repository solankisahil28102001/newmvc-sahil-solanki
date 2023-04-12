<?php 

class Model_Customer_Address_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('customer_address')->setPrimaryKey('address_id');
	}
}

