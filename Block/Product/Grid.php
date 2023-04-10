<?php 

class Block_Product_Grid extends Block_Core_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('product/grid.phtml');	
	}

	public function getProducts()
	{
		$query = "SELECT * FROM `product` ORDER BY `product_id` DESC";
		$products = Ccc::getModel('Product')->fetchAll($query);
		return $products;
	}
}