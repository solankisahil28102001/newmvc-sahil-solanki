<?php 

class Block_Item_Grid extends Block_Core_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setTitle('Manage Items');
	}
	
	public function getCollection()
	{
		$query = "SELECT count(`entity_id`) FROM `item`";
		$tableRecordes = Ccc::getModel('Item')->fetchOne($query);

		$pager = $this->getPager();
		$pager->setCurrentPage($this->getCurrentPage())
			->setTotalRecords($tableRecordes)
			->setRecordPerPage($this->getRecordPerPage())
			->calculate();
		$startLimit = $pager->getStartLimit();
		$recordPerPage = $pager->getRecordPerPage();
		
		$query = "SELECT I.*, IDPrice.`value` as price, IDCost.`value` as cost, IDName.`value` as name  
		FROM `item` I
		LEFT JOIN `item_decimal` IDPrice ON I.`entity_id` = IDPrice.`entity_id` AND IDPrice.`attribute_id` = '22' 
		LEFT JOIN `item_decimal` IDCost ON I.`entity_id` = IDCost.`entity_id` AND IDCost.`attribute_id` = '23	' 
		LEFT JOIN `item_varchar` IDName ON I.`entity_id` = IDName.`entity_id` AND IDName.`attribute_id` = '21' 
		ORDER BY `entity_id` DESC LIMIT {$startLimit},{$recordPerPage}";
		$items = Ccc::getModel('Item')->fetchAll($query);
		return $items;
	}

	protected function _prepareColumns()
	{
		$this->addColumn('entity_id', [
			'title' => 'Entity Id'
		]);

		$this->addColumn('entity_type_id', [
			'title' => 'Entity type Id'
		]);

		$this->addColumn('name', [
			'title' => 'Name',
		]);

		$this->addColumn('sku', [
			'title' => 'Sku',
		]);

		$this->addColumn('price', [
			'title' => 'Price',
		]);

		$this->addColumn('cost', [
			'title' => 'Cost',
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
		$this->addButton('add_item', [
			'title' => 'Add New',
			'url' => $this->getUrl('add', null, null, true)
		]);
		return parent::_prepareButtons();
	}

}