<?php 

class Model_Eav_Attribute_Option_Source extends Model_Eav_Attribute_Option 
{
	
	public function getOptions()
	{
		$query = "SELECT * FROM `eav_attribute_option` WHERE `attribute_id` = '{$this->getAttribute()->getId()}' ORDER BY `position` DESC";
		return $this->fetchAll($query);
	}
}