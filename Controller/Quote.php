<?php 

class Controller_Quote extends Controller_Core_Action
{
	public function quoteAction()
	{
		$layout = $this->getLayout();
		$quote = $layout->createBlock('Quote_Quote');

		$this->loadByCustomerId();

		$layout->getChild('content')->addChild('quote', $quote);
		$layout->render();
	}


	public function loadByCustomerId()
	{
		if ($customerId = $this->getRequest()->getParam('customerId')) {
			$customer = Ccc::getModel('Customer')->load($customerId);
		}		
	}

	public function customerAddressSaveAction()
	{
		// code...
	}

	public function paymentSaveAction()
	{
		// code...
	}

	public function shippingSaveAction()
	{
			
	}

	public function itemsSaveAction()
	{
		
	}


}