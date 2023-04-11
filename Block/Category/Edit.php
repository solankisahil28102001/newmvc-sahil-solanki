<?php 

class Block_Category_Edit extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('category/edit.phtml');
	}

	public function getCategory()
	{
		return $this->category;
	}

	public function getPathCategories()
	{
		return $this->pathCategories;
	}

}