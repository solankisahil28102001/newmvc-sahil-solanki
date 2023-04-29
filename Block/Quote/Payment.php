<?php 

class Block_Quote_Payment extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('quote/payment.phtml');
	}

	public function getPaymentMethods()
	{
		$query = "SELECT * FROM `payment_method` ORDER BY `payment_method_id`";
		return Ccc::getModel('Payment')->fetchAll($query);
	}

	public function getQuote()
	{
		// if ($quote = Ccc::getModel('Quote')->load($this->getRequest()->getParam('customerId'), 'customer_id')) {
		// 	return $quote;
		// }
		return Ccc::getModel('Quote')->getQuote();
	}
}