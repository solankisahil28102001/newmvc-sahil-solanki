<?php 

class Model_Quote_Address_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('quote_address')->setPrimaryKey('address_id');
	}
}