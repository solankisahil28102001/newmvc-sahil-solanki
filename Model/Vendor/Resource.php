<?php 

class Model_Vendor_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('vendor')->setPrimaryKey('vendor_id');
	}
}