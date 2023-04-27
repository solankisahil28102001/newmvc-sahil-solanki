<?php 

class Block_Vendor_Grid extends Block_Core_Grid
{
	function __construct()
	{
		parent::__construct();
		$this->setTitle('Manage vendors');
	}

	public function getCollection()
	{
		$query = "SELECT count(`vendor_id`) FROM `vendor`";
		$tableRecordes = Ccc::getModel('Vendor')->fetchOne($query);

		$pager = $this->getPager();
		$pager->setCurrentPage($this->getCurrentPage())
			->setTotalRecords($tableRecordes)
			->setRecordPerPage($this->getRecordPerPage())
			->calculate();
		$startLimit = $pager->getStartLimit();
		$recordPerPage = $pager->getRecordPerPage();
		
		$query = "SELECT * FROM `vendor` ORDER BY `vendor_id` DESC LIMIT {$startLimit},{$recordPerPage}";
		$vendors = Ccc::getModel('Vendor')->fetchAll($query);
		return $vendors;
	}

	protected function _prepareColumns()
	{
		$this->addColumn('vendor_id', [
			'title' => 'Vendor Id'
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
		$this->addButton('add_vendor', [
			'title' => 'Add New',
			'url' => $this->getUrl('add', null, null, true)
		]);
		return parent::_prepareButtons();
	}
}