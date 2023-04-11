<?php 

class Block_Admin_Edit extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('admin/edit.phtml');
	}

	public function getAdmin()
	{
		return $this->admin;
	}

}