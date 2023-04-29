<?php 

class Block_Order_Grid extends Block_Core_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setTitle('Manage Orders');
	}


	public function getCollection()
	{
		$query = "SELECT count(`order_id`) FROM `orders`";
		$tableRecordes = Ccc::getModel('Order')->fetchOne($query);

		$pager = $this->getPager();
		$pager->setCurrentPage($this->getCurrentPage())
			->setTotalRecords($tableRecordes)
			->setRecordPerPage($this->getRecordPerPage())
			->calculate();
		$startLimit = $pager->getStartLimit();
		$recordPerPage = $pager->getRecordPerPage();
		
		$query = "SELECT * FROM `orders` ORDER BY `order_id` DESC LIMIT {$startLimit},{$recordPerPage}";
		$orders = Ccc::getModel('Order')->fetchAll($query);
		return $orders;
	}

	protected function _prepareColumns()
	{
		$this->addColumn('order_id', [
			'title' => 'Order Id'
		]);

		$this->addColumn('customer_id', [
			'title' => 'Customer Id'
		]);

		$this->addColumn('customer_email', [
			'title' => 'Customer email',
		]);

		$this->addColumn('customer_name', [
			'title' => 'Customer name',
		]);

		$this->addColumn('customer_mobile_no', [
			'title' => 'Customer mobile no',
		]);

		$this->addColumn('order_total', [
			'title' => 'Order Total',
		]);

		$this->addColumn('status', [
			'title' => 'Status',
		]);

		$this->addColumn('payment_method_id', [
			'title' => 'Payment method Id',
		]);

		$this->addColumn('shipping_method_id', [
			'title' => 'shipping_method Id',
		]);

		$this->addColumn('shipping_amount', [
			'title' => 'Shipping amount',
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
		$this->addButton('add_order', [
			'title' => 'Add New',
			'url' => $this->getUrl('quote','quote', null, true)
		]);
		return parent::_prepareButtons();
	}

}