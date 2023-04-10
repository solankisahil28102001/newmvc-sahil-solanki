<?php

class Block_Payment_Edit extends Block_Core_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('payment/edit.phtml');
	}

	public function getPayment()
	{
		return $this->payment;
	}

}