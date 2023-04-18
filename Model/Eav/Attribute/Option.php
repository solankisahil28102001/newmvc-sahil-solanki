<?php 

class Model_Eav_Attribute_Option extends Model_Core_Table
{
	protected $_attribute = null;

	function __construct()
	{
		parent::__construct();
		$this->setResourceClass('Model_Eav_Attribute_Option_Resource');
		$this->setCollectionClass('Model_Eav_Attribute_Option_Collection');
	}

	public function getAttribute()
	{
		return $this->_attribute;
	}

	public function setAttribute($attribute)
	{
		$this->_attribute = $attribute;
		return $this;

	}
}