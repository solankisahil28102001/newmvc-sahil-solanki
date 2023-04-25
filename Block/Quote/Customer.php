<?php 

class Block_Quote_Customer extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('quote/customer.phtml');
	}

	public function getCustomers()
	{
		return Ccc::getModel('Customer')->getCustomers();
	}
}