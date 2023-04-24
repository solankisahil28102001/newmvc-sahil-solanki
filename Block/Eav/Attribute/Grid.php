<?php 

class Block_Eav_Attribute_Grid extends Block_Core_Grid
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTitle('Manage Eav Attributes');
	}

	public function getCollection()
	{
		$query = "SELECT * FROM `eav_attribute` ORDER BY `attribute_id` DESC";
		$attributes = Ccc::getModel('Eav_Attribute')->fetchAll($query);
		return $attributes;
	}

	protected function _prepareColumns()
	{
		$this->addColumn('attribute_id', [
			'title' => 'Attribute Id'
		]);

		$this->addColumn('entity_name', [
			'title' => 'Entity name'
		]);

		$this->addColumn('name', [
			'title' => 'Name'
		]);

		$this->addColumn('code', [
			'title' => 'Code'
		]);

		$this->addColumn('input_type', [
			'title' => 'Input type'
		]);

		$this->addColumn('backend_type', [
			'title' => 'Backend type'
		]);

		$this->addColumn('status', [
			'title' => 'Status',
		]);

		$this->addColumn('backend_model', [
			'title' => 'Backend Model'
		]);

		$this->addColumn('source_model', [
			'title' => 'Source Model'
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
		$this->addButton('add_eav_attribute', [
			'title' => 'Add New',
			'url' => $this->getUrl('add', null, null, true)
		]);
		return parent::_prepareButtons();
	}
	
}