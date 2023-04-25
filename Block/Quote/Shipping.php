<?php 

class Block_Quote_Shipping extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('quote/shipping.phtml');
	}

	public function getShippingMethods()
	{
		$query = "SELECT * FROM `shipping_method` ORDER BY `shipping_method_id`";
		return Ccc::getModel('Shipping')->fetchAll($query);
	}

	public function getQuote()
	{
		if ($quote = Ccc::getModel('Quote')->load($this->getRequest()->getParam('customerId'), 'customer_id')) {
			return $quote;
		}
		return Ccc::getModel('Quote');
	}
}