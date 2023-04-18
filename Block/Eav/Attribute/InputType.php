<?php 

class Block_Eav_Attribute_InputType extends Block_Core_Template
{
	protected $_attribute = null;
	protected $_row = null;

	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('eav/attribute/inputType.phtml');
	}

	public function setAttribute(Model_Eav_Attribute $attribute)
	{
		$this->_attribute = $attribute;
		return $this;
	}

	public function getAttribute()
	{
		return $this->_attribute;
	}

	public function setRow(Model_Item $row)
	{
		$this->_row = $row;
		return $this;
	}

	public function getRow()
	{
		return $this->_row;
	}

	public function getInputTypeField()
	{
		$inputType = $this->getAttribute()->input_type;
		if ($inputType == 'text') {
			return $this->getLayout()->createBlock('Eav_Attribute_InputType_Text')->setAttribute($this->getAttribute())->setRow($this->getRow());
		}
		elseif ($inputType == 'select') {
			return $this->getLayout()->createBlock('Eav_Attribute_InputType_Select')->setAttribute($this->getAttribute())->setRow($this->getRow());
		}
		elseif ($inputType == 'multiselect') {
			return $this->getLayout()->createBlock('Eav_Attribute_InputType_MultiSelect')->setAttribute($this->getAttribute())->setRow($this->getRow());
		}
		elseif ($inputType == 'textarea') {
			return $this->getLayout()->createBlock('Eav_Attribute_InputType_Textarea')->setAttribute($this->getAttribute())->setRow($this->getRow());
		}
		elseif ($inputType == 'radio') {
			return $this->getLayout()->createBlock('Eav_Attribute_InputType_Radio')->setAttribute($this->getAttribute())->setRow($this->getRow());
		}
		elseif ($inputType == 'checkbox') {
			return $this->getLayout()->createBlock('Eav_Attribute_InputType_Checkbox')->setAttribute($this->getAttribute())->setRow($this->getRow());
		}
		elseif ($inputType == 'decimal') {
			return $this->getLayout()->createBlock('Eav_Attribute_InputType_Decimal')->setAttribute($this->getAttribute())->setRow($this->getRow());
		}
		else{
			echo "Invalid inputType";
		}
	}
	
}