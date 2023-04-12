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
		$productId = $this->getRequest()->getParam('id');
		$query = "SELECT * FROM `product_media` WHERE `product_id` = $productId ORDER BY `media_id` DESC";
		$medias = Ccc::getModel('Product_Media')->fetchAll($query);
		return $medias;
	}

	public function getBase()
	{
		$productId = $this->getRequest()->getParam('id');
		$product = Ccc::getModel('Product')->load($productId);
		return $product->base_id;
	}

	public function getThumb()
	{
		$productId = $this->getRequest()->getParam('id');
		$product = Ccc::getModel('Product')->load($productId);
		return $product->thumb_id;	
	}

	public function getSmall()
	{
		$productId = $this->getRequest()->getParam('id');
		$product = Ccc::getModel('Product')->load($productId);
		return $product->small_id;
	}
}