<?php

class Block_Item_Edit extends Block_Core_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('item/edit.phtml');
	}

	public function getRow()
	{
		return $this->item;
	}

	public function getAttributes()
	{
		return Ccc::getModel('Item')->getAttributes();
	}
}