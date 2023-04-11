<?php 

class Block_Html_Left extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('html/left.phtml');
	}
}