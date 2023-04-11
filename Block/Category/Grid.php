<?php 

class Block_Category_Grid extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('category/grid.phtml');
	}

	public function fetchData()
	{
		$category = Ccc::getModel('Category');
		$query = "SELECT * FROM `category` WHERE `category_id` > 1 ORDER BY `path`";
		$categories = $category->fetchAll($query);
		$pathCategories = $category->preparePathCategories();
		$this->setData(['categories' => $categories, 'pathCategories' => $pathCategories]);
		return $this;
	}

	public function getCategories()
	{
		return $this->categories;
	}

	public function getPathCategories()
	{
		return $this->pathCategories;
	}

	
}