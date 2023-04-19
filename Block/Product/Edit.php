<?php

class Block_Product_Edit extends Block_Core_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('product/edit.phtml');
	}

	public function getProduct()
	{
		return $this->product;
	}

	public function getAttributes()
	{
		return Ccc::getModel('Product')->getAttributes();
	}

}