<?php 

class Block_Quote_Address extends Block_Core_Template
{
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('quote/address.phtml');
	}
		
	public function getShippingAddress()
	{
		if ($quoteAddress = Ccc::getModel('Quote_Address')->load($this->getCustomer()->shipping_address_id, 'customer_address_id')) {
			return $quoteAddress;
		}
		if ($customerAddress = Ccc::getModel('Customer_Address')->load($this->getCustomer()->shipping_address_id)) {
			return $customerAddress;
		}
		return Ccc::getModel('Quote_Address');
	}

	public function getBillingAddress()
	{
		if ($quoteAddress = Ccc::getModel('Quote_Address')->load($this->getCustomer()->billing_address_id, 'customer_address_id')) {
			return $quoteAddress;
		}
		if ($customerAddress = Ccc::getModel('Customer_Address')->load($this->getCustomer()->billing_address_id)) {
			return $customerAddress;
		}
		return Ccc::getModel('Quote_Address');
	}

	public function getCustomer()
	{
		$id = Ccc::getModel('Core_Session')->get('customer_id');
		if ($id) {
			return Ccc::getModel('Customer')->load($id);
		}
		return Ccc::getModel('Customer');
	}
}