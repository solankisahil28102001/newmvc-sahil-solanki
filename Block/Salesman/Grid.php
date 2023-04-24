<?php 

class Block_Salesman_Grid extends Block_Core_Grid
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTitle('Manage Salesmen');
	}

	public function getCollection()
	{
		$query = "SELECT * FROM `salesman` ORDER BY `salesman_id` DESC";
		$salesmen = Ccc::getModel('Salesman')->fetchAll($query);
		return $salesmen;
	}

	protected function _prepareColumns()
	{
		$this->addColumn('salesman_id', [
			'title' => 'Salesman Id'
		]);

		$this->addColumn('first_name', [
			'title' => 'First name'
		]);

		$this->addColumn('last_name', [
			'title' => 'Last name',
		]);

		$this->addColumn('email', [
			'title' => 'Email',
		]);

		$this->addColumn('gender', [
			'title' => 'Gender',
		]);

		$this->addColumn('mobile', [
			'title' => 'Mobile',
		]);

		$this->addColumn('status', [
			'title' => 'Status',
		]);

		$this->addColumn('company', [
			'title' => 'Company',
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
			'title' => 'Price',
			'method' => 'getPriceUrl',
			'name' => 'price'
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
		$this->addButton('add_salesman', [
			'title' => 'Add New',
			'url' => $this->getUrl('add', null, null, true)
		]);
		return parent::_prepareButtons();
	}
}