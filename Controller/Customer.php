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

			$customerAddress = Ccc::getModel('Customer');
			$customerAddress->getResource()->setTableName('customer_address');
			if (!$customerAddress) {
				throw new Exception("Invalid request.", 1);
			}

			$edit->setData(['customer' => $customer, 'customerAddress' => $customerAddress]);
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

			$customerAddress = Ccc::getModel('Customer');
			$customerAddress->getResource()->setTableName('customer_address');
			if (!$customerAddress->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$edit->setData(['customer' => $customer, 'customerAddress' => $customerAddress]);
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

			if (!$postData2 = $this->getRequest()->getPost('customerAddress')) {
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

			$customerAddress = Ccc::getModel('Customer');
			$customerAddress->getResource()->setTableName('customer_address');
			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$customerAddress->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}
			}
			else{
				$customerAddress->customer_id = $customer->customer_id;
				$customerAddress->getResource()->setPrimaryKey('address_id');
			}

			$customerAddress->setData($postData2);
			if (!$customerAddress->save()) {
				throw new Exception("Unable to save customerAddress", 1);
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