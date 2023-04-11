<?php

class Block_Eav_Attribute_Edit extends Block_Core_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('eav/attribute/edit.phtml');
	}

	public function getAttribute()
	{
		return $this->attribute;
	}

	public function getOptions()
	{
		return $this->options;
	}

}