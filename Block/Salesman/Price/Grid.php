<?php

class Block_Salesman_Price_Grid extends Block_Core_Template
{
	private $salesmanId = null;

	function __construct()
	{
		parent::__construct();
		$this->setTemplate('salesman/price/grid.phtml');
	}

	 public function getSalesmanId()
    {
        return $this->salesmanId;
    }

    public function setSalesmanId($salesmanId)
    {
        $this->salesmanId = $salesmanId;
        return $this;
    }

	public function fetchData()
	{
		$id = $this->getSalesmanId();
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