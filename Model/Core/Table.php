<?php

class Model_Core_Table
{
	protected $data = [];
	protected $resourceClass = 'Model_Core_Table_Resource';
	protected $collectionClass = 'Model_Core_Table_Collection';
	protected $resource = null;
	protected $collection = null;

	public function __construct()
	{

	}

	public function setResourceClass($resourceClass)
	{
		$this->resourceClass = $resourceClass;
		return $this;
	}
	
	public function setCollectionClass($collectionClass)
	{
		$this->collectionClass = $collectionClass;
		return $this;
	}


	public function setId($id)
	{
		$this->data[$this->getPrimaryKey()] = (int) $id;
		return $this;
	}

	public function getId()
	{
		return $this->{$this->getPrimaryKey()};
	}


	protected function setResource($resource)
	{
		$this->resource = $resource;
		return  $this;
	}

	public function getResource()
	{
		if ($this->resource) {
			return $this->resource;
		}
		$resourceClass = $this->resourceClass;
		$resource = new $resourceClass();
		$this->setResource($resource);
		return $resource;
	}

	protected function setCollection($collection)
	{
		$this->collection = $collection;
		return  $this;
	}

	public function getCollection()
	{
		if ($this->collection) {
			return $this->collection;
		}
		$collectionClass = $this->collectionClass;
		$collection = new $collectionClass();
		$this->setCollection($collection);
		return $collection;
	}

	public function getPrimaryKey()
	{
		return $this->getResource()->getPrimaryKey();
	}

	public function getTableName()
	{
		return $this->getResource()->getTableName();
	}

	public function __set($key, $value)
	{
		$this->data[$key] = $value;
	}

	public function __get($key)
	{
		if (array_key_exists($key, $this->data)) {
			return $this->data[$key];
		}
		return null;
	}

	public function __unset($key)
	{
		if (array_key_exists($key, $this->data)) {
			unset($this->data[$key]);
		}
	}

	public function getData($key=null)
	{
		if ($key == null) {
			if ($this->data) {
				return $this->data;
			}
			return null;
		}
		if (array_key_exists($key, $this->data)) {
			return $this->data[$key];
		}
		return null;
	}

	public function setData($data)
	{
		$this->data = array_merge($this->data, $data);
		return $this;
	}

	public function addData($key, $value)
	{
		$this->data[$key] = $value;
		return $this;
	}

	public function removeData($key = null)
	{
		if ($key == null) {
			$this->setData([]);
		}
		if (array_key_exists($key, $this->data)) {
			unset($this->data[$key]);
			return $this;
		}
		return null;
	}

	public function save()
	{
		if ($id = $this->getId()) {
			$this->removeData($this->getPrimaryKey());
			$condition = [$this->getPrimaryKey() => $id];
			$result = $this->getResource()->update($this->getData(), $condition);
			if ($result) {
				$this->load($id);
				return true;
			}
			return false;
		}

		$insert_id = $this->getResource()->insert($this->getData());
		if ($insert_id) {
			$this->load($insert_id);
			return $insert_id;
		}

		return false;
	}

	public function fetchAll($query)
	{
		$result = $this->getResource()->fetchAll($query);
		if (!$result) {
			return false;
		}

		$rows = [];
		foreach ($result as $key => $row) {
			$rows[$key] = (new $this)->setData($row)->setResource($this->getResource())->setCollection($this->getCollection());
		}

		$collection = $this->getCollection()->setData($rows);
		return $collection;
	}


	public function fetchRow($query)
	{
		$row = $this->getResource()->fetchRow($query);
		if (!$row) {
			return false;
		}	

		$this->setData($row);
		return $this;
	}

	public function fetchOne($query)
	{
		return $this->getResource()->fetchOne($query);
	}

	public function fetchPairs($query)
	{
		return $this->getResource()->fetchPairs($query);
	}


	public function load($id, $column=null)
	{
		if ($id == null) {
			return null;
		}
		if ($column == null) {
			$column = $this->getPrimaryKey();
		}
		
		if (is_array($id)) {
			$id = rtrim(implode(",",$id), ",");
		}

		$query = "SELECT * FROM `{$this->getTableName()}` WHERE `{$column}` IN($id)";
		if ($row = $this->getResource()->fetchRow($query)) {
			$this->data = $row;
			return $this;
		}
		return false;
	}
	
	public function delete()
	{
		$id = $this->getData($this->getPrimaryKey());
		if (!$id) {
			return false;
		}

		$condition = [$this->getPrimaryKey() => $id];
		$result = $this->getResource()->delete($condition);
		if ($result == true) {
			$this->removeData();
			return true;
		}
		return false;
	}

}