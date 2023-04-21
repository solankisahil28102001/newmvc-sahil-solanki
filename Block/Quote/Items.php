<?php 

class Block_Quote_Items extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('quote/items.phtml');
	}

	public function getItems()
	{
		$query = "SELECT * FROM `product`";
		return Ccc::getModel('Item')->fetchAll($query);
	}
}