<?php 

class Block_Quote_Total extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('quote/total.phtml');
	}
}