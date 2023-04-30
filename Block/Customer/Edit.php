<?php 

class Block_Customer_Edit extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('customer/edit.phtml');
	}

	public function getCustomer()
	{
		return $this->customer;
	}

	public function getShippingAddress()
	{
		return $this->shippingAddress;
	}

	public function getBillingAddress()
	{
		return $this->billingAddress;
	}

	public function getAttributes()
	{
		return Ccc::getModel('Customer')->getAttributes();
	}
}