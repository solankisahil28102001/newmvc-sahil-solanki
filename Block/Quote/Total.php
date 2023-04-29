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
		if ($id = Ccc::getModel('Quote')->getQuote()->quote_id) {
			$query = "SELECT sum(((QI.`discount` * P.`price`)/100) * QI.`quantity`) as discountAmount FROM `product` P LEFT JOIN `quote_item` QI ON P.`product_id` = QI.`product_id` AND QI.`quote_id` = {$id}";
			return Ccc::getModel('Quote_Item')->fetchRow($query)->discountAmount;
		}
		return null;
	}

	public function getTotal()
	{
		if ($id = Ccc::getModel('Quote')->getQuote()->quote_id) {
			$query = "SELECT sum(P.`price` * QI.`quantity`) as total FROM `product` P LEFT JOIN `quote_item` QI ON P.`product_id` = QI.`product_id` AND QI.`quote_id` = {$id}";
			return Ccc::getModel('Quote_Item')->fetchRow($query)->total;
		}
		return 0;
	}

	public function getShippingAmount()
	{
		$customerId = $this->getRequest()->getParam('customerId');
		$query = "SELECT `shipping_amount` FROM `quote` WHERE `customer_id` = '{$customerId}'";
		return Ccc::getModel('Quote')->getResource()->getAdapter()->fetchOne($query);
	}

	public function setOrderTotal($total)
	{

		$quote = Ccc::getModel('Quote')->getQuote();
		unset($quote->quote_id);
		unset($quote->payment_method_id);
		unset($quote->shipping_method_id);
		if ($quote->customer_id != null) {
			$quote->setData(['order_total' => $total,'customer_id' => $quote->customer_id])->getResource()->setPrimaryKey('customer_id');
			$quote->save();
		}
		return $this;
	}
}