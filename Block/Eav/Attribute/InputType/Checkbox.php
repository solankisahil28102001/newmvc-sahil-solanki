<?php

class Block_Eav_Attribute_InputType_Checkbox extends Block_Core_Template
{
	protected $_attribute = null;
	protected $_row = null;

	function __construct()
	{
		parent::__construct();
		$this->setTemplate('eav/attribute/inputType/checkbox.phtml');
	}

	public function setAttribute($attribute)
	{
		$this->_attribute = $attribute;
		return $this;
	}

	public function getAttribute()
	{
		return $this->_attribute;
	}

	public function setRow($row)
	{
		$this->_row = $row;
		return $this;
	}

	public function getRow()
	{
		return $this->_row;
	}
}