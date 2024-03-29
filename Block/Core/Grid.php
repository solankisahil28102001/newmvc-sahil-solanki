<?php 

class Block_Core_Grid extends Block_Core_Template
{
	protected $_columns = [];
	protected $_buttons = [];
	protected $_actions = [];
	protected $_title = null;
	protected $pager = null;
	protected $currentPage = null;
	protected $recordPerPage = 10;

	function __construct()
	{
		parent::__construct();	
		$this->setTemplate('core/grid.phtml');
		$this->_prepareActions();
		$this->_prepareButtons();
		$this->_prepareColumns();
	}
	
	public function getPager()
	{
		if ($this->pager) {
			return $this->pager;
		}
		$pager = new Model_Core_Pager();
		$this->setPager($pager);
		return $pager;
	}

	public function setPager(Model_Core_Pager $pager)
	{
		$this->pager = $pager;
		return $this;
	}

	public function setRecordPerPage($recordPerPage)
	{
		$this->recordPerPage = $recordPerPage;
		return $this;
	}

	public function getRecordPerPage()
	{
		return $this->recordPerPage;
	}

	public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }

	public function getTitle()
	{
		return $this->_title;
	}

	public function setTitle($title)
	{
		$this->_title = $title;
		return $this;
	}

	public function getColumns()
	{
		return $this->_columns;
	}

	public function setColumns(array $columns)
	{
		$this->_columns = $columns;
		return $columns;
	}

	public function addColumn($key, $value)
	{
		$this->_columns[$key] = $value;
		return $this;
	}

	public function removeColumn($key)
	{
		unset($this->_columns[$key]);
		return $this;
	}

	public function getColumn($key)
	{
		if (array_key_exists($key, $this->_columns)) {
			return $this->_columns[$key];
		}
		return null;
	}

	protected function _prepareColumns()
	{
		return $this;
	}

	public function getColumnValue($row, $key)
	{	
		if ($key == 'status') {
			return $row->getStatusText();
		}

		if ($key == 'color') {
			return $row->getColor();
		}

		if ($key == 'material') {
			return $row->getMaterial();
		}

		if ($key == 'entity_name') {
			return $row->getEntityName();
		}

		if ($row instanceof Model_Category) {
			if ($key == 'name') {
				$pathCategories = $row->preparePathCategories();
				if (array_key_exists($row->category_id, $pathCategories)) {
					return $pathCategories[$row->category_id];
				}
				return null;
			}
		}
		return $row->$key;
	}

	public function getActions()
	{
		return $this->_actions;
	}

	public function setActions(array $actions)
	{
		$this->_actions = $actions;
		return $actions;
	}

	public function addAction($key, $value)
	{
		$this->_actions[$key] = $value;
		return $this;
	}

	public function removeAction($key)
	{
		unset($this->_actions[$key]);
		return $this;
	}

	public function getAction($key)
	{
		if (array_key_exists($key, $this->_actions)) {
			return $this->_actions[$key];
		}
		return null;
	}

	public function getEditUrl($row, $key)
	{
		return $this->getUrl($key, null, ['id' => $row->getId()], true);
	}

	public function getDeleteUrl($row, $key)
	{
		return $this->getUrl($key, null, ['id' => $row->getId()], true);
	}

	public function getPriceUrl($row, $key)
	{
		return $this->getUrl($key, 'salesman_price', ['id' => $row->getId()], true);
	}

	public function getMediaUrl($row, $key)
	{
		return $this->getUrl($key, 'product_media', ['id' => $row->getId()], true);
	}

	protected function _prepareActions()
	{
		return $this;
	}

	public function getButtons()
	{
		return $this->_buttons;
	}

	public function setButtons(array $buttons)
	{
		$this->_buttons = $buttons;
		return $buttons;
	}

	public function addButton($key, $value)
	{
		$this->_buttons[$key] = $value;
		return $this;
	}

	public function removeButton($key)
	{
		unset($this->_buttons[$key]);
		return $this;
	}

	public function getButton($key)
	{
		if (array_key_exists($key, $this->_buttons)) {
			return $this->_buttons[$key];
		}
		return null;
	}

	protected function _prepareButtons()
	{
		return $this;
	}

}