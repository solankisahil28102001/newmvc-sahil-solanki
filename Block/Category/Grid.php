<?php 

class Block_Category_Grid extends Block_Core_Grid
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTitle('Manage Categories');
	}

	public function getCollection()
	{
		$category = Ccc::getModel('Category');
		$query = "SELECT * FROM `category` WHERE `category_id` > 1 ORDER BY `path`";
		$categories = $category->fetchAll($query);
		return $categories;
	}

	protected function _prepareColumns()
	{
		$this->addColumn('category_id', [
			'title' => 'Category Id'
		]);

		$this->addColumn('name', [
			'title' => 'Name'
		]);

		$this->addColumn('status', [
			'title' => 'Status',
		]);

		$this->addColumn('description', [
			'title' => 'Description',
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
		$this->addButton('add_category', [
			'title' => 'Add New',
			'url' => $this->getUrl('add', null, null, true)
		]);
		return parent::_prepareButtons();
	}
	
}