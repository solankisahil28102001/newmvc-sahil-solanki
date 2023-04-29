<?php 

class Block_Brand_Grid extends Block_Core_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setTitle('Manage Brands');
	}
	

	public function getCollection()
	{
		$query = "SELECT count(`brand_id`) FROM `brand`";
		$tableRecordes = Ccc::getModel('Brand')->fetchOne($query);

		$pager = $this->getPager();
		$pager->setCurrentPage($this->getCurrentPage())
			->setTotalRecords($tableRecordes)
			->setRecordPerPage($this->getRecordPerPage())
			->calculate();
		$startLimit = $pager->getStartLimit();
		$recordPerPage = $pager->getRecordPerPage();
		
		$query = "SELECT * FROM `brand` ORDER BY `brand_id` LIMIT {$startLimit},{$recordPerPage}";
		$brands = Ccc::getModel('Brand')->fetchAll($query);
		return $brands;
	}

	protected function _prepareColumns()
	{
		$this->addColumn('brand_id', [
			'title' => 'Brand Id'
		]);

		$this->addColumn('name', [
			'title' => 'Name'
		]);

		$this->addColumn('description', [
			'title' => 'Description'
		]);

		$this->addColumn('image', [
			'title' => 'image'
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
		$this->addButton('add_brand', [
			'title' => 'Add New',
			'url' => $this->getUrl('add')
		]);
		return parent::_prepareButtons();
	}

}