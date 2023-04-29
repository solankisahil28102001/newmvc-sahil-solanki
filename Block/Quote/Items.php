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

	public function getQuoteItems()
	{
		if ($quote_id = Ccc::getModel('Quote')->getQuote()->quote_id){
			$query = "SELECT P.`name`,P.`price`,QI.* FROM `product` P JOIN `quote_item` QI ON P.`product_id` = QI.`product_id` AND QI.`quote_id` = {$quote_id}";
			return Ccc::getModel('Quote_Item')->fetchAll($query);
		}
		return null;
	}

	public function getTotal()
	{
		$query = "SELECT sum((QI.`price` - ((QI.`discount` * QI.`price`) / 100)) * QI.`quantity`) as total FROM `product` P LEFT JOIN `quote_item` QI ON P.`product_id` = QI.`product_id`";
		return Ccc::getModel('Quote_Item')->fetchRow($query);
	}
}