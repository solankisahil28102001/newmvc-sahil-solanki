<?php 

class Block_Payment_Grid extends Block_Core_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('payment/grid.phtml');	
	}

	public function getPayments()
	{
		$query = "SELECT * FROM `payment_method` ORDER BY `payment_method_id` DESC";
		$payments = Ccc::getModel('Payment')->fetchAll($query);
		return $payments;
	}
}