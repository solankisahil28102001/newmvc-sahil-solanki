<?php 

class Block_Quote_Total extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('quote/total.phtml');
	}

	public function getDiscountAmount()
	{
		$query = "SELECT sum(((QI.`discount` * P.`price`)/100) * QI.`quantity`) as discountAmount FROM `product` P LEFT JOIN `quote_item` QI ON P.`product_id` = QI.`product_id`";
		return Ccc::getModel('Quote_Item')->fetchRow($query)->discountAmount;
	}

	public function getTotal()
	{
		$query = "SELECT sum(P.`price` * QI.`quantity`) as total FROM `product` P LEFT JOIN `quote_item` QI ON P.`product_id` = QI.`product_id`";
		return Ccc::getModel('Quote_Item')->fetchRow($query);
	}

	public function getShippingAmount()
	{
		$customerId = $this->getRequest()->getParam('customerId');
		$query = "SELECT `shipping_amount` FROM `quote` WHERE `customer_id` = '{$customerId}'";
		return Ccc::getModel('Quote')->getResource()->getAdapter()->fetchOne($query);
	}
}