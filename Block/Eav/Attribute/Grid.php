<?php 

class Block_Eav_Attribute_Grid extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('eav/attribute/grid.phtml');
	}

	public function getAttributes()
	{
		$query = "SELECT * FROM `eav_attribute` ORDER BY `attribute_id` DESC";
		$attributes = Ccc::getModel('Eav_Attribute')->fetchAll($query);
		return $attributes;
	}
}