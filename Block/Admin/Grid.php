<?php 

class Block_Admin_Grid extends Block_Core_Grid
{
	function __construct()
	{
		parent::__construct();
		$this->setTitle('Manage Admins');
	}

	public function getCollection()
	{
		$query = "SELECT count(`admin_id`) FROM `admin`";
		$tableRecordes = Ccc::getModel('Admin')->fetchOne($query);

		$pager = $this->getPager();
		$pager->setCurrentPage($this->getCurrentPage())
			->setTotalRecords($tableRecordes)
			->setRecordPerPage($this->getRecordPerPage())
			->calculate();
		$startLimit = $pager->getStartLimit();
		$recordPerPage = $pager->getRecordPerPage();
		
		$query = "SELECT * FROM `admin` ORDER BY `admin_id` DESC LIMIT {$startLimit},{$recordPerPage}";
		$admins = Ccc::getModel('Admin')->fetchAll($query);
		return $admins;
	}

	protected function _prepareColumns()
	{
		$this->addColumn('admin_id', [
			'title' => 'Admin Id'
		]);

		$this->addColumn('name', [
			'title' => 'Name'
		]);

		$this->addColumn('email', [
			'title' => 'Email'
		]);

		$this->addColumn('status', [
			'title' => 'Status'
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
		$this->addButton('add_admin', [
			'title' => 'Add New',
			'url' => $this->getUrl('add', null, null, true)
		]);
		return parent::_prepareButtons();
	}
}