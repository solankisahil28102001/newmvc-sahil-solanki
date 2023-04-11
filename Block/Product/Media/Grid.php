<?php 

class Block_Product_Media_Grid extends Block_Core_Template
{		
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('product/media/grid.phtml');
	}

	public function getMedias()
	{
		$productId = Ccc::getRegistry('product_id');
		$query = "SELECT * FROM `product_media` WHERE `product_id` = $productId ORDER BY `media_id` DESC";
		$medias = Ccc::getModel('Product_Media')->fetchAll($query);
		return $medias;
	}
}