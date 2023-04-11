<?php 

class Model_Shipping_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('shipping_method')->setPrimaryKey('shipping_method_id');
	}
}