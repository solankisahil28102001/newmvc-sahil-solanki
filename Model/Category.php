<?php

class Model_Category extends Model_Core_Table
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 2;
	const STATUS_ACTIVE_LBL = 'Active';
	const STATUS_INACTIVE_LBL = 'Inactive';
	const STATUS_DEFAULT = 2;

	public function __construct()
	{	
		parent::__construct();
		$this->setResourceClass('Model_Category_Resource');
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

	public function getParentCategories()
	{	
		$query = "SELECT `category_id`,`path` FROM `{$this->getResource()->getTableName()}` WHERE `category_id` > 1 ORDER BY `path`";
		$categories = $this->getResource()->getAdapter()->fetchPairs($query);
		return $categories;
	}

	public function updatePath()
	{
		if (!$this->getId()) {
			return false;
		}

		$parent = Ccc::getModel('Category')->load($this->parent_id);
		$oldPath = $this->path;
		if (!$parent) {
			$this->path = $this->getId();
		}
		else{
			$this->path = $parent->path."-".$this->getId();
		}

		unset($this->updated_at);
		$this->save();

		$query = "UPDATE `category` SET `path` = REPLACE(`path`, '{$oldPath}-', '{$this->path}-') WHERE `path` LIKE '{$oldPath}-%'";
		$this->getResource()->getAdapter()->update($query);
		return $this;
	}

	public function showParents()
	{
		$query = "SELECT * FROM `category` WHERE (`path` NOT LIKE '{$this->path}-%' AND `path` NOT LIKE '{$this->path}')";
		if (!$categories = $this->fetchAll($query)){
			return false;
		}

		$pathCategories = [];
		foreach($categories->getData() as $row){
			if ($row->path) {
				$pathArray = explode("-",$row->path);
				foreach ($pathArray as &$value) {
					$value = $row->load($value)->name;
				}
				$row->path = implode(" > ", $pathArray);
				$pathCategories[$row->category_id] = ltrim($row->path, 'Root > ');
			}
		}
		return $pathCategories;
	}

	public function preparePathCategories()
	{
		$category = Ccc::getModel('Category');

		$query = "SELECT * FROM `category` WHERE `category_id` > 1 ORDER BY `path`";
		if (!$categories = $category->fetchAll($query)){
			return false;
		}
		
		$pathCategories = [];
		foreach($categories->getData() as $row){
			if ($row->path) {
				$pathArray = explode("-",$row->path);
				foreach ($pathArray as &$value) {
					$value = $row->load($value)->name;
				}
				$row->path = implode(" > ", $pathArray);
				$pathCategories[$row->category_id] = ltrim($row->path, 'Root > ');
			}
		}

		return $pathCategories;
	}

	public function getAttributes()
	{
		$query = "SELECT * FROM `eav_attribute` WHERE `entity_type_id` = 6 AND `status` = '".self::STATUS_ACTIVE."'";
		return Ccc::getModel('Eav_Attribute')->fetchAll($query);
	}

	public function getAttributeValue($attribute)
	{
		$query = "SELECT `value` FROM `category_{$attribute->backend_type}` WHERE `attribute_id` = '{$attribute->attribute_id}' AND `entity_id` = '{$this->getId()}'";
		return $this->getResource()->getAdapter()->fetchOne($query);
	}

}

