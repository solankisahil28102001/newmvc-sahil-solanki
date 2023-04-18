<?php 

class Model_Eav_Attribute extends Model_Core_Table
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 2;
	const STATUS_ACTIVE_LBL = 'Active';
	const STATUS_INACTIVE_LBL = 'Inactive';
	const STATUS_DEFAULT = 2;
	
	function __construct()
	{
		parent::__construct();
		$this->setResourceClass('Model_Eav_Attribute_Resource');
		$this->setCollectionClass('Model_Eav_Attribute_Collection');
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

	public function getEntityName()
	{
		if ($row = Ccc::getModel('EntityType')->load($this->entity_type_id)){
			return $row->name;
		}
		return null;
	}

	public function getEntityNames()
	{
		$query = "SELECT `entity_type_id`,`name` FROM `entity_type` ORDER BY `name`";
		if (!$row = $this->getResource()->getAdapter()->fetchPairs($query)) {
			return null;
		}
		return $row;
	}
	
	public function getOptions()
	{
		$sourceModel = $this->source_model;
		if (!$sourceModel) {
			$sourceModel = 'Eav_Attribute_Option_Source';
		}
		$query = "SELECT * FROM `eav_attribute_option` WHERE `attribute_id` = '".$this->attribute_id."' ORDER BY `position`";
		return Ccc::getModel($sourceModel)->setAttribute($this)->getOptions();
	}

	
}