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
		if ($customer = $this->getCustomer()) {
			return Ccc::getModel('Customer_Address')->load($customer->shipping_address_id);
		}
		return Ccc::getModel('Customer_Address');
	}

	public function getBillingAddress()
	{
		if ($customer = $this->getCustomer()) {
			return Ccc::getModel('Customer_Address')->load($customer->billing_address_id);
		}
		return Ccc::getModel('Customer_Address');	
	}

	public function getCustomer()
	{
		$id = $this->getRequest()->getParam('customerId');
		if ($id) {
			return Ccc::getModel('Customer')->load($id);
		}
		return null;
	}
}