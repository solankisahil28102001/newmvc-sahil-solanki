<?php 

class Model_Quote_Item_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('quote_item')->setPrimaryKey('item_id');
	}
}