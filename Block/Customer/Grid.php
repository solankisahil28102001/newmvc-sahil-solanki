<?php 

class Block_Customer_Grid extends Block_Core_Grid
{
	function __construct()
	{
		parent::__construct();
		$this->setTitle('Manage Customers');
	}
	
	public function getCollection()
	{
		$query = "SELECT count(`customer_id`) FROM `customer`";
		$tableRecordes = Ccc::getModel('Customer')->fetchOne($query);

		$pager = $this->getPager();
		$pager->setCurrentPage($this->getCurrentPage())
			->setTotalRecords($tableRecordes)
			->setRecordPerPage($this->getRecordPerPage())
			->calculate();
		$startLimit = $pager->getStartLimit();
		$recordPerPage = $pager->getRecordPerPage();
		
		$query = "SELECT * FROM `customer` ORDER BY `customer_id` DESC LIMIT {$startLimit},{$recordPerPage}";
		$customers = Ccc::getModel('Customer')->fetchAll($query);
		return $customers;
	}

	protected function _prepareColumns()
	{
		$this->addColumn('customer_id', [
			'title' => 'Customer Id'
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

		$this->addColumn('billing_address_id', [
			'title' => 'Billing address id',
		]);

		$this->addColumn('shipping_address_id', [
			'title' => 'Shipping address id',
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
		$this->addButton('add_customer', [
			'title' => 'Add New',
			'url' => $this->getUrl('add', null, null, true)
		]);
		return parent::_prepareButtons();
	}
}