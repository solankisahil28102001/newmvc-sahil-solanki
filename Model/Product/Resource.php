<?php 

class Model_Product_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('product')->setPrimaryKey('product_id');
	}
}