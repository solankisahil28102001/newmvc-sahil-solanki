<?php

class Model_Quote extends Model_Core_Table
{
	public function __construct()
	{	
		parent::__construct();
		$this->setResourceClass('Model_Quote_Resource');
	}

	public function getQuote()
	{
		if ($id = Ccc::getModel('Core_Session')->get('customer_id')) {
			if ($quote = $this->load($id, 'customer_id')) {
				return $quote;
			}
		}
		return $this;
	}
}