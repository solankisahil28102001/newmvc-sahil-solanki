<?php 

class Model_Core_Table_Resource
{
	protected $adapter = null;
	protected $tableName = null;
	protected $primaryKey = null;

	public function __construct()
	{
		
	}

	public function setTableName($tableName)
	{
		$this->tableName = $tableName;
		return $this;
	}

	public function getTableName()
	{
		return $this->tableName;
	}

	public function setPrimaryKey($primaryKey)
	{
		$this->primaryKey = $primaryKey;
		return $this;
	}

	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	public function getAdapter()
	{
		if ($this->adapter) {
			return $this->adapter;
		}
		$adapter = new Model_Core_Adapter();
		$this->setAdapter($adapter);
		return $adapter;
	}

	public function setAdapter(Model_Core_Adapter $adapter)
	{
		$this->adapter = $adapter;
		return $this;
	}

	public function fetchRow($query)
	{	
		return $this->getAdapter()->fetchRow($query);
	}

	public function fetchAll($query)
	{
		return $this->getAdapter()->fetchAll($query);
	}

	public function insert($data)
	{
		$keys = "`".implode('`,`', array_keys($data))."`";
		$values = "'".implode("','", array_values($data))."'";
		
		$query = "INSERT INTO `{$this->getTableName()}` ($keys) VALUES ($values)";
		return $this->getAdapter()->insert($query);
	}

	public function update($data,$conditions)
	{
		$condition = "";
		$count = 0;
		foreach ($conditions as $key => $value) {
			if ($count == 0) {
				if (is_array($value)) {
					$condition .= "`".$key."` IN(".rtrim(implode(',',$value),",").")";
				}
				elseif ($value) {
					$condition .= "`".$key."` = '".$value."'";
				}
			}
			if ($count > 0) {
				if (is_array($value)) {
					$condition .= " AND `".$key."` IN(".rtrim(implode(',',$value),",").")";
				}
				elseif ($value) {
					$condition .= " AND `".$key."` = '".$value."'";
				}
			}
			$count++;
		}	

		$keys = array_keys($data);

		$str = "";
		for ($i=0; $i < count($data); $i++) { 
			$str .= "`".$keys[$i]."`="."'".$data[$keys[$i]]."',";
		}
		$keyValue = rtrim($str, ",");

		$query = "UPDATE `{$this->getTableName()}` SET $keyValue WHERE $condition";
		echo $query;
		echo '<br>';
		return $this->getAdapter()->update($query);
	}

	public function delete($conditions)
	{
		$condition = "";
		$count = 0;
		foreach ($conditions as $key => $value) {
			if ($count == 0) {
				if (is_array($value)) {
					$condition .= "`".$key."` IN(".rtrim(implode(',',$value),",").")";
				}
				elseif ($value) {
					$condition .= "`".$key."` = '".$value."'";
				}
			}
			if ($count > 0) {
				if (is_array($value)) {
					$condition .= " AND `".$key."` IN(".rtrim(implode(',',$value),",").")";
				}
				elseif ($value) {
						$condition .= " AND `".$key."` = '".$value."'";
				}
			}
			$count++;
		}	

		$query = "DELETE FROM `{$this->getTableName()}` WHERE $condition";
		return $this->getAdapter()->delete($query);
	}
}