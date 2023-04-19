<?php

class Model_Shipping extends Model_Core_Table
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 2;
	const STATUS_ACTIVE_LBL = 'Active';
	const STATUS_INACTIVE_LBL = 'Inactive';
	const STATUS_DEFAULT = 2;

	public function __construct()
	{	
		parent::__construct();
		$this->setResourceClass('Model_Shipping_Resource');
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

	public function getStatus()
	{
		if ($this->status) {
			return $this->status;
		}
		return self::STATUS_DEFAULT;
	}

	public function getAttributes()
	{
		$query = "SELECT * FROM `eav_attribute` WHERE `entity_type_id` = 8 AND `status` = '".self::STATUS_ACTIVE."'";
		return Ccc::getModel('Eav_Attribute')->fetchAll($query);
	}

	public function getAttributeValue($attribute)
	{
		$query = "SELECT `value` FROM `shipping_{$attribute->backend_type}` WHERE `entity_id` = '{$this->getId()}' AND `attribute_id` = '{$attribute->getId()}'";
		return $this->getResource()->getAdapter()->fetchOne($query);
	}

}