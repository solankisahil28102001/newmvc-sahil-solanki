<?php

class Block_Salesman_Price_Grid extends Block_Core_Template
{
	
	function __construct()
	{
		parent::__construct();
		$this->setTemplate('salesman/price/grid.phtml');
	}

	public function fetchData()
	{
		$id = Ccc::getRegistry('salesman_id');
		$query = "SELECT * FROM `salesman` ORDER BY `first_name`";
		$salesmanNames = Ccc::getModel('Salesman_Price')->fetchAll($query);

		$query = "SELECT SP.*,P.* FROM `product` P LEFT JOIN `salesman_price` SP ON P.`product_id`=SP.`product_id` AND SP.`salesman_id` = {$id}";
		$salesmanPrices = Ccc::getModel('Salesman_Price')->fetchAll($query);

		$this->setData(['salesmanNames' => $salesmanNames, 'salesmanPrices' => $salesmanPrices]);
		return $this;
	}

	public function getSalesmanNames()
	{	
		return $this->salesmanNames;
	}

	public function getSalesmanPrices()
	{	
		return $this->salesmanPrices;
	}
}