<?php

class Model_Product extends Model_Core_Table
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 2;
	const STATUS_ACTIVE_LBL = 'Active';
	const STATUS_INACTIVE_LBL = 'Inactive';
	const STATUS_DEFAULT = 2;

	public function __construct()
	{	
		parent::__construct();
		$this->setResourceClass('Model_Product_Resource');
	}
	
	public function getStatusOptions()
	{
		return [
			self::STATUS_ACTIVE => self::STATUS_ACTIVE_LBL,
			self::STATUS_INACTIVE => self::STATUS_INACTIVE_LBL
		];
	}


	public function getStatusText()
	{
		$statues = $this->getStatusOptions();
		if (array_key_exists($this->status, $statues)) {
			return $statues[$this->status];
		}
		return $statues[self::STATUS_DEFAULT];
	}

	public function getColor()
	{
		$color = ['1'=>'Brown','2'=>'Black','3'=>'White','4'=>'Red','5'=>'Silver','6'=>'Blue','7'=>'Green'];
		if (array_key_exists($this->color, $color)) {
		 	return $color[$this->color];
		 } 
		 return '';
	}

	public function getMaterial()
	{
		$material = ['1'=>'Plastic','2'=>'Glass','3'=>'Metals','4'=>'Wood','5'=>'Paper','6'=>'Fibers','7'=>'Ceramic'];
		if (array_key_exists($this->material, $material)) {
		 	return $material[$this->material];
		 } 
		 return '';
	}

	public function getStatus()
	{
		if ($this->status) {
			return $this->status;
		}
		return self::STATUS_DEFAULT;
	}

	public function getAttributes()
	{
		$query = "SELECT * FROM `eav_attribute` WHERE `entity_type_id` = 2 AND `status` = '".self::STATUS_ACTIVE."'";
		return Ccc::getModel('Eav_Attribute')->fetchAll($query);
	}

	public function getAttributeValue($attribute)
	{
		$query = "SELECT `value` FROM `product_{$attribute->backend_type}` WHERE `entity_id` = '{$this->getId()}' AND `attribute_id` = '{$attribute->getId()}'";
		return $this->getResource()->getAdapter()->fetchOne($query);
	}
}