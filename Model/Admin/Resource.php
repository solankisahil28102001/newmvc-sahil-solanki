<?php

class Model_Admin_Resource extends Model_Core_Table_Resource
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableName('admin')->setPrimaryKey('admin_id');
	}
}