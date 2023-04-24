<?php 

class Block_Shipping_Grid extends Block_Core_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setTitle('Manage Shipping Method');	
	}

	public function getCollection()
	{
		$query = "SELECT * FROM `shipping_method` ORDER BY `shipping_method_id` DESC";
		$shippings = Ccc::getModel('Shipping')->fetchAll($query);
		return $shippings;
	}

	protected function _prepareColumns()
	{
		$this->addColumn('shipping_method_id', [
			'title' => 'Shipping Method Id'
		]);

		$this->addColumn('name', [
			'title' => 'Name'
		]);

		$this->addColumn('amount', [
			'title' => 'Amount',
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
		$this->addButton('add_shipping_method', [
			'title' => 'Add New',
			'url' => $this->getUrl('add', null, null, true)
		]);
		return parent::_prepareButtons();
	}
}