<?php 

class Controller_Quote extends Controller_Core_Action
{
	public function quoteAction()
	{
		try {
			$customerId = $this->getRequest()->getParam('customerId');
			$this->getSession()->set('customer_id', $customerId);
			$quote = Ccc::getModel('Quote');
			if (!$quote->getQuote()->customer_id && $customerId != null) {
				$quote->setData(['customer_id' => $customerId]);
				$quote->save();
			}
			$quoteHtml = $this->getLayout()->createBlock('Quote_Quote')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $quoteHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);			
		}
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
			if ($this->getSession()->get('customer_id')) {
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

					if ($checked = $this->getRequest()->getPost('billingAddressBook')) {
						$customerAddress = Ccc::getModel('Customer_Address');
						$customerAddress->setData($billingAddress);
						$customerAddress->address_id = $this->loadByCustomerId()->billing_address_id;

						if (!$customerAddress->save()) {
							throw new Exception("Unable to save customer address", 1);
						}
					}

					$quoteAddress = Ccc::getModel('Quote_Address');
					$quoteAddress->setData($billingAddress);
					$quoteAddress->customer_address_id = $this->loadByCustomerId()->billing_address_id;
					$quoteAddress->quote_id = Ccc::getModel('Quote')->getQuote()->quote_id;
					if (!$quoteAddress->save()) {
						throw new Exception("Unable to save billing address", 1);
					}

					if ($checked = $this->getRequest()->getPost('markAsShipping')) {
						unset($quoteAddress->address_id);
						if (!Ccc::getModel('Quote_Address')->load($this->loadByCustomerId()->shipping_address_id, 'customer_address_id')) {
							$quoteAddress->customer_address_id = $this->loadByCustomerId()->shipping_address_id;
							if (!$quoteAddress->save()) {
								throw new Exception("Unable to save shippingAddress", 1);
							}
						}
					}
				}
				
				elseif ($action == 'Save Shipping address') {
					if (!$shippingAddress = $this->getRequest()->getPost('shippingAddress')) {
						throw new Exception("Invalid request.", 1);
					}

					if ($checked = $this->getRequest()->getPost('shippingAddressBook')) {
						$customerAddress = Ccc::getModel('Customer_Address');
						$customerAddress->setData($shippingAddress);
						$customerAddress->address_id = $this->loadByCustomerId()->shipping_address_id;

						if (!$customerAddress->save()) {
							throw new Exception("Unable to save customer address", 1);
						}
					}

					$quoteAddress = Ccc::getModel('Quote_Address');
					$quoteAddress->setData($shippingAddress);
					$quoteAddress->customer_address_id = $this->loadByCustomerId()->shipping_address_id;
					$quoteAddress->quote_id = Ccc::getModel('Quote')->getQuote()->quote_id;
					if (!$quoteAddress->save()) {
						throw new Exception("Unable to save billing address", 1);
					}
				}

				else{
					throw new Exception("Invalid request.", 1);
				}
			$this->getMessage()->addMessage("Address saved successfully.");
			}
			else{
				$this->getMessage()->addMessage('Please select the customer.', Model_Core_Message::NOTICE);
			}

			$quoteHtml = $this->getLayout()->createBlock('Quote_Quote')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $quoteHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);			
			$this->redirect('index', 'order', null, true);
		}
	}

	public function savePaymentAction()
	{
		try {
			if ($this->getSession()->get('customer_id')) {
				if (!$this->getRequest()->getPost()) {
					throw new Exception("Invalid request.", 1);
				}

				if (!$payment = $this->getRequest()->getPost('payment')) {
					throw new Exception("Invalid request.", 1);
				}
				$quote = Ccc::getModel('Quote')->getQuote();
				unset($quote->quote_id);
				unset($quote->shipping_method_id);
				$quote->setData(['payment_method_id' => $payment,'customer_id' => $this->getRequest()->getParam('customerId')])->getResource()->setPrimaryKey('customer_id');
				if (!$quote->save()) {
					throw new Exception("Unable to save payment method", 1);
				}
			$this->getMessage()->addMessage('Payment method updated successfully.');
			}	
			else{
				$this->getMessage()->addMessage('Please select the customer.', Model_Core_Message::NOTICE);
			}

			$quoteHtml = $this->getLayout()->createBlock('Quote_Quote')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $quoteHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);			
			$this->redirect('index', 'order', null, true);
		}
	}

	public function saveShippingAction()
	{
		try {
			if ($this->getSession()->get('customer_id')) {
				if (!$this->getRequest()->getPost()) {
					throw new Exception("Invalid request.", 1);
				}

				if (!$shipping = $this->getRequest()->getPost('shipping')) {
					throw new Exception("Invalid request.", 1);
				}
				$amount = Ccc::getModel('Shipping')->load($shipping)->amount;
				$quote = Ccc::getModel('Quote')->getQuote();
				unset($quote->quote_id);
				unset($quote->payment_method_id);
				$quote->setData(['shipping_method_id' => $shipping, 'shipping_amount' => $amount])->getResource()->setPrimaryKey('customer_id');
				$quote->customer_id = $this->getRequest()->getParam('customerId');
				if (!$quote->save()) {
					throw new Exception("Unable to save shipping method", 1);
				}
			$this->getMessage()->addMessage('Shipping method updated successfully.');
			}
			else{
				$this->getMessage()->addMessage('Please select the customer.', Model_Core_Message::NOTICE);
			}

			$quoteHtml = $this->getLayout()->createBlock('Quote_Quote')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $quoteHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);			
			$this->redirect('index', 'order', null, true);
		}
	}

	public function itemsSaveAction()
	{
		try {
			if ($this->getSession()->get('customer_id')) {
				if (!$this->getRequest()->isPost()) {
					throw new Exception("Invalid request.", 1);
				}

				if (!$selected = $this->getRequest()->getPost('add')) {
					throw new Exception("Invalid request.", 1);
				}
				if (!$items = $this->getRequest()->getPost('item')) {
					throw new Exception("Invalid request.", 1);
				}
				$quote_id = Ccc::getModel('Quote')->getQuote()->quote_id;
				foreach ($selected as $productId => $value) {
					$product = Ccc::getModel('Product')->load($productId);
					$quoteItem = Ccc::getModel('Quote_Item');
					if ($quoteItem->load($productId, 'product_id')){
						$quantity = (int)$quoteItem->quantity + (int)$items[$productId];
						$quoteItem->setData(['product_id' => $productId, 'quantity' => $quantity, 'price' => $product->price, 'quote_id' => $quote_id])->getResource()->setPrimaryKey('product_id');
					}
					else{
						$quoteItem->setData(['product_id' => $productId, 'quantity' => $items[$productId], 'price' => $product->price, 'quote_id' => $quote_id]);
					}
					if (!$quoteItem->save()) {
						throw new Exception("Unable to save quoteItem.", 1);
					}
				}
			$this->getMessage()->addMessage('Items inserted successfully.');
			}
			else{
				$this->getMessage()->addMessage('Please select the customer.', Model_Core_Message::NOTICE);
			}
			$quoteHtml = $this->getLayout()->createBlock('Quote_Quote')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $quoteHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);			
			$this->redirect('index', 'order', null, true);
		}
	}

	public function quoteItemsUpdateAction()
	{
		try {
			if ($this->getSession()->get('customer_id')) {
				if (!$this->getRequest()->isPost()) {
					throw new Exception("Invalid request.", 1);
				}

				if (!$items = $this->getRequest()->getPost('quoteItem')) {
					throw new Exception("Invalid request.", 1);
				}
				foreach ($items as $itemId => $array) {
					foreach ($array as $column => $value) {
						if ($column == 'discount') {
							$value = ($value > 100) ? 100 : $value;
							$value = ($value < 0) ? 0 : $value;
						}
						$quoteItem = Ccc::getModel('Quote_Item');
						$quoteItem->setData(['item_id' => $itemId, $column => $value]);
						if (!$quoteItem->save()) {
							throw new Exception("Unable to update $column.", 1);
						}
					}
				}
			$this->getMessage()->addMessage('Quote items updated successfully.');
			}
			else{
				$this->getMessage()->addMessage('Please select the customer.', Model_Core_Message::NOTICE);
			}

			$quoteHtml = $this->getLayout()->createBlock('Quote_Quote')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $quoteHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);			
		}
	}

	public function deleteQuoteItemAction()
	{
		try {
			if (!$itemId = (int)$this->getRequest()->getParam('item_id')) {
				throw new Exception("Invalid request.", 1);
			}

			$quoteItem =  Ccc::getModel('Quote_Item')->setData(['item_id' => $itemId]);
			if (!$quoteItem->delete()) {
				throw new Exception("Unable to delete item.", 1);
			}
			$quoteHtml = $this->getLayout()->createBlock('Quote_Quote')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $quoteHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);	
			$this->redirect('index', 'order', null, true);
		}
	}
}