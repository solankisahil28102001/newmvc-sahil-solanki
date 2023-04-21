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
			return Ccc::getModel('Customer')->load($customerId);
		}
		return Ccc::getModel('Customer');		
	}

	public function saveAddressAction()
	{
		try {
			if (!$this->getRequest()->isPost()) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$action = $this->getRequest()->getPost('submit')) {
				throw new Exception("Invalid request.", 1);
			}

			if ($action == 'Save Billing address') {
				if (!$billingAddress = $this->getRequest()->getPost('billingAddress')) {
					throw new Exception("Invalid request.", 1);
				}

				$quoteAddress = Ccc::getModel('Quote_Address');
				$quoteAddress->setData($billingAddress);
				$quoteAddress->customer_address_id = $this->loadByCustomerId()->billing_address_id;
				if (!$quoteAddress->save()) {
					throw new Exception("Unable to save billing address", 1);
				}

				if ($checked = $this->getRequest()->getPost('markAsShipping')) {
					unset($quoteAddress->address_id);
					$quoteAddress->customer_address_id = $this->loadByCustomerId()->shipping_address_id;
					if (!$quoteAddress->save()) {
						throw new Exception("Unable to save shippingAddress", 1);
					}
				}
			}
			
			if ($action == 'Save Shipping address') {
				if (!$shippingAddress = $this->getRequest()->getPost('shippingAddress')) {
					throw new Exception("Invalid request.", 1);
				}
				$quoteAddress = Ccc::getModel('Quote_Address');
				$quoteAddress->setData($shippingAddress);
				$quoteAddress->customer_address_id = $this->loadByCustomerId()->shipping_address_id;
				if (!$quoteAddress->save()) {
					throw new Exception("Unable to save billing address", 1);
				}
			}

		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);			
		}
		$this->redirect('quote', null, null, null, true);
	}

	public function savePaymentAction()
	{
		try {
			if (!$this->getRequest()->getPost()) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$payment = $this->getRequest()->getPost('payment')) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$customerId = $this->loadByCustomerId()->customer_id) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$quote = Ccc::getModel('Quote')->load($customerId)) {
				throw new Exception("Invalid id", 1);
			}
		} catch (Exception $e) {
			
		}
	}

	public function saveShippingAction()
	{
			
	}

	public function itemsSaveAction()
	{
		
	}


}