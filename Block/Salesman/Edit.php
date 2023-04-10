<?php 

class Block_Salesman_Edit extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('salesman/edit.phtml');
	}

	public function getSalesman()
	{
		return $this->salesman;
	}

	public function getSalesmanAddress()
	{
		return $this->salesmanAddress;
	}
}