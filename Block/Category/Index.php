<?php 

class Block_Category_Index extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('category/index.phtml');
	}
}