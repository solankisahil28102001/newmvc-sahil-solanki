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
	
	protected function _saveCustomer()
	{
		if (!$postData = $this->getRequest()->getPost('customer')) {
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

		$customer->setData($postData);
		if (!$customer->save()) {
			throw new Exception("Unable to save customer", 1);
		}
		return $customer;
	}

	public function _saveBillingAddress($customer)
	{
		if (!$postData = $this->getRequest()->getPost('billingAddress')) {
			throw new Exception("No data posted.", 1);
		}

		$billingAddress = $customer->getBillingAddress();
		if (!$billingAddress) {
			$billingAddress = Ccc::getModel('Customer_Address');
			$billingAddress->updated_at = date('Y-m-d H:i:s');
		}
		else{
			$billingAddress->created_at = date('Y-m-d H:i:s');
		}
		$billingAddress->setData($postData);
		$billingAddress->customer_id = $customer->getId();
		if (!$billingAddressId = $billingAddress->save()) {
			throw new Exception("Unable to save billingAddress", 1);
		}

		return $billingAddress;
	}

	public function _saveShippingAddress($customer)
	{
		if (!$this->getRequest()->getPost('checkbox')) {
			$postData = $this->getRequest()->getPost('shippingAddress');
		}
		else{
			$postData = $this->getRequest()->getPost('billingAddress');
		}
		if (!$postData) {
			throw new Exception("No data posted.", 1);
		}

		$shippingAddress = $customer->getShippingAddress();
		if (!$shippingAddress) {
			$shippingAddress = Ccc::getModel('Customer_Address');
			$shippingAddress->updated_at = date('Y-m-d H:i:s');
		}
		else{
			$shippingAddress->created_at = date('Y-m-d H:i:s');
		}
		$shippingAddress->setData($postData);
		$shippingAddress->customer_id = $customer->getId();
		if (!$shippingAddressId = $shippingAddress->save()) {
			throw new Exception("Unable to save shippingAddress", 1);
		}
		return $shippingAddress;
	}

	public function saveAction()
	{
		try {
			if (!$this->getRequest()->isPost()) {		
				throw new Exception("Invalid request.", 1);
			}

			$customer = $this->_saveCustomer();
			$billingAddress = $this->_saveBillingAddress($customer);
			$shippingAddress = $this->_saveShippingAddress($customer);

			$customer->billing_address_id = $billingAddress->address_id;
			$customer->shipping_address_id = $shippingAddress->address_id;
			unset($customer->updated_at);
			if (!$customer->save()) {
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