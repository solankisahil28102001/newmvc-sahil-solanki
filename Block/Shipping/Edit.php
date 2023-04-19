<?php

class Block_Shipping_Edit extends Block_Core_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('shipping/edit.phtml');
	}

	public function getShipping()
	{
		return $this->shipping;
	}

	public function getAttributes()
	{
		return Ccc::getModel('Shipping')->getAttributes();
	}
}