<?php 

class Block_Product_Grid extends Block_Core_Grid
{
	protected $pager = null;

	public function getPager()
	{
		return $this->pager;
	}

	public function setPager(Model_Core_Pager $pager)
	{
		$this->pager = $pager;
	}

	public function __construct()
	{
		parent::__construct();
		$this->setTitle('Manage Products');	
	}

	public function getCollection()
	{
		$query = "SELECT * FROM `product` ORDER BY `product_id` DESC";
		$products = Ccc::getModel('Product')->fetchAll($query);

		$query = "SELECT count(`product_id`) FROM `product`";
		$tableRecores = Ccc::getModel('Product')->fetchOne($query);
		$currentPage = $this->getRequest()->getParam('p');
		var_dump($currentPage);
		$pager = new Model_Core_Pager($tableRecores, $currentPage);
		$this->setPager($pager);
		return $products;
	}

	protected function _prepareColumns()
	{
		$this->addColumn('product_id', [
			'title' => 'Product Id'
		]);

		$this->addColumn('name', [
			'title' => 'Name'
		]);

		$this->addColumn('sku', [
			'title' => 'Sku',
		]);

		$this->addColumn('cost', [
			'title' => 'Cost',
		]);

		$this->addColumn('price', [
			'title' => 'Price',
		]);

		$this->addColumn('quantity', [
			'title' => 'Quantity',
		]);

		$this->addColumn('description', [
			'title' => 'Description',
		]);

		$this->addColumn('status', [
			'title' => 'Status',
		]);

		$this->addColumn('color', [
			'title' => 'Color',
		]);

		$this->addColumn('material', [
			'title' => 'Material',
		]);

		$this->addColumn('small_id', [
			'title' => 'Small Id',
		]);

		$this->addColumn('thumb_id', [
			'title' => 'Thumb Id',
		]);

		$this->addColumn('base_id', [
			'title' => 'Base Id',
		]);

		$this->addColumn('created_at', [
			'title' => 'Created At'
		]);

		$this->addColumn('updated_at', [
			'title' => 'Updated At'
		]);

		return parent::_prepareColumns();
	}

	protected function _prepareActions()
	{
		$this->addAction('grid', [
			'title' => 'Media',
			'method' => 'getMediaUrl',
			'name' => 'media'
		]);

		$this->addAction('edit', [
			'title' => 'Edit',
			'method' => 'getEditUrl'
		]);

		$this->addAction('delete', [
			'title' => 'Delete',
			'method' => 'getDeleteUrl'
		]);

		return parent::_prepareActions();
	}

	protected function _prepareButtons()
	{
		$this->addButton('add_product', [
			'title' => 'Add New',
			'url' => $this->getUrl('add', null, null, true)
		]);
		return parent::_prepareButtons();
	}
}