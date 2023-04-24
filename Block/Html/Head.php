<?php 

class Block_Html_Head extends Block_Core_Template
{
	protected $_title = null;
	protected $javascripts = [];
	protected $stylesheets = [];

	function __construct()
	{
		parent::__construct();
		$this->setTemplate('html/head.phtml');
	}

	public function addJs($src)
	{
		$this->javascripts[] = $src;
		return $this;
	}

	public function getAllJs()
	{
		return $this->javascripts;
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
}