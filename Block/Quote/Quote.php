<?php 

class Block_Quote_Quote extends Block_Core_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('quote/quote.phtml');	
		$this->prepareChildren();
	}

	public function prepareChildren()
	{
		$address = $this->createBlock('Address');
		$address->setQuote($this);
		$this->addChild('address', $address);

		$total = $this->createBlock('Total');
		$total->setQuote($this);
		$this->addChild('total', $total);

		$shipping = $this->createBlock('Shipping');
		$shipping->setQuote($this);
		$this->addChild('shipping', $shipping);

		$payment = $this->createBlock('Payment');
		$payment->setQuote($this);
		$this->addChild('payment', $payment);

		$items = $this->createBlock('Items');
		$items->setQuote($this);
		$this->addChild('items', $items);

		$customer = $this->createBlock('Customer');
		$customer->setQuote($this);
		$this->addChild('customer', $customer);
	}

	public function createBlock($className)
	{
		$className = "Block_Quote_".$className;
		$block = new $className();
		$block->setQuote($this);
		return $block;
	}
}