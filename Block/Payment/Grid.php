<?php 

class Block_Payment_Grid extends Block_Core_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setTitle('Manage Payment Method');
	}


	public function getCollection()
	{
		$query = "SELECT * FROM `payment_method` ORDER BY `payment_method_id` DESC";
		$payments = Ccc::getModel('Payment')->fetchAll($query);
		return $payments;
	}

	protected function _prepareColumns()
	{
		$this->addColumn('payment_method_id', [
			'title' => 'Payment Method Id'
		]);

		$this->addColumn('name', [
			'title' => 'Name'
		]);

		$this->addColumn('status', [
			'title' => 'Status',
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
		$this->addButton('add_payment_method', [
			'title' => 'Add New',
			'url' => $this->getUrl('add', null, null, true)
		]);
		return parent::_prepareButtons();
	}

}