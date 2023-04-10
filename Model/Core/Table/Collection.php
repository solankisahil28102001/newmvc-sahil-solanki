<?php 

class Model_Core_Table_Collection	
{
	
	protected $data = [];


	public function setData($data)
	{
		$this->data = array_merge($this->data, $data);
		return $this;
	}

	public function getData()
	{
		return $this->data;
	}

	public function count()
	{
		return count($this->data);
	}

	public function getFirst()
	{
		if (array_key_exists(0, $this->data)) {
			return $this->data[0];
		}
		return null;
	}

	public function getLast()
	{
		$index = count($this->data)-1;
		if (array_key_exists($index, $this->data)) {
			return $this->data[$index];
		}
		return null;
	}
}