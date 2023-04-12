<?php 

class Controller_Customer extends Controller_Core_Action{

	public function addAction()
	{
		try {
			$layout = $this->getLayout();
			$edit = $layout->createBlock('Customer_Edit');
			if (!$customer = Ccc::getModel('Customer')) {
				throw new Exception("Invalid request.", 1);
			}

			$edit->setData(['customer' => $customer, 'shippingAddress' => $customer, 'billingAddress' => $customer]);
			$layout->getChild('content')->addChild('edit', $edit);
			$layout->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('grid', null, null, true);
		}
	}


	public function editAction()
	{	
		try {
			$layout = $this->getLayout();
			$edit = $layout->createBlock('Customer_Edit');
			if (!$id = (int)$this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$customer = Ccc::getModel('Customer')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			if (!$shippingAddress = $customer->getShippingAddress()) {
				throw new Exception("Invalid Id.", 1);
			}

			if (!$billingAddress = $customer->getBillingAddress()) {
				throw new Exception("Invalid Id.", 1);
			}

			$edit->setData(['customer' => $customer, 'shippingAddress' => $shippingAddress, 'billingAddress' => $billingAddress]);
			$layout->getChild('content')->addChild('edit', $edit);
			$layout->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('grid', null, null, true);
		}
	}

	public function gridAction()
	{
		try {
			$layout = $this->getLayout();
			$grid = $layout->createBlock('Customer_Grid');
			$layout->getChild('content')->addChild('grid', $grid);
			$layout->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
	}
	
	public function saveAction()
	{
		try {
			if (!$this->getRequest()->isPost()) {		
				throw new Exception("Invalid request.", 1);
			}

			if (!$postData1 = $this->getRequest()->getPost('customer')) {
				throw new Exception("No data posted.", 1);
			}

			if (!$postData2 = $this->getRequest()->getPost('billingAddress')) {
				throw new Exception("No data posted.", 1);
			}

			if (!$postData3 = $this->getRequest()->getPost('shippingAddress')) {
				throw new Exception("No data posted.", 1);
			}


			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$customer = Ccc::getModel('Customer')->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}

				$customer->updated_at = date('Y-m-d H:i:s');
			}
			else{
				$customer = Ccc::getModel('Customer');
				$customer->created_at = date('Y-m-d H:i:s');
			}

			$customer->setData($postData1);
			if (!$customer->save()) {
				throw new Exception("Unable to save customer", 1);
			}

			$billingAddress = Ccc::getModel('Customer');
			$billingAddress->getResource()->setTableName('customer_address')->setPrimaryKey('address_id');
			if ($id = $customer->billing_address_id) {
				if (!$billingAddress->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}
				$billingAddress->setData($postData2);
				if (!$billingAddressId = $billingAddress->save()) {
					throw new Exception("Unable to save billingAddress", 1);
				}
			}
			else{
				$billingAddress->customer_id = $customer->customer_id;
				$billingAddress->getResource()->setPrimaryKey('address_id');
				$billingAddress->setData($postData2);
				if (!$billingAddressId = $billingAddress->save()) {
					throw new Exception("Unable to save billingAddress", 1);
				}
				$customer->billing_address_id = $billingAddressId;
			}
			
			$shippingAddress = Ccc::getModel('Customer');
			$shippingAddress->getResource()->setTableName('customer_address')->setPrimaryKey('address_id');
			if ($id = $customer->shipping_address_id) {
				if (!$shippingAddress->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}
				$shippingAddress->setData($postData3);
				if (!$shippingAddressId = $shippingAddress->save()) {
					throw new Exception("Unable to save shippingAddress", 1);
				}
			}
			else{
				$shippingAddress->customer_id = $customer->customer_id;
				$shippingAddress->getResource()->setPrimaryKey('address_id');
				$shippingAddress->setData($postData3);
				if (!$shippingAddressId = $shippingAddress->save()) {
					throw new Exception("Unable to save shippingAddress", 1);
				}
				$customer->shipping_address_id = $shippingAddressId;
				
			}

			unset($customer->updated_at);
			if (!$customer->save()){
				throw new Exception("Unable to save customer", 1);
			}

			$this->getMessage()->addMessage('Customer saved successfully.');
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
		}
		$this->redirect('grid', null, null, true);
	}

	public function deleteAction()
	{
		try {
			if (!$id = (int)$this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$customer = Ccc::getModel('Customer')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			if (!$customer->delete()){
				throw new Exception("Unable to delete customer.", 1);
			}

			$this->getMessage()->addMessage("Customer deleted successfully.");
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
		$this->redirect('grid', null, null, true);
	}
}