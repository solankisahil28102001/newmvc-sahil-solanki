<?php

class Controller_Vendor extends Controller_Core_Action
{
	public function addAction()
	{
		try {
			$layout = $this->getLayout();
			$edit = $layout->createBlock('Vendor_Edit');
			
			$vendor = Ccc::getModel('Vendor');		
			if (!$vendor) {
				throw new Exception("Invalid request.", 1);
			}

			$vendorAddress = Ccc::getModel("Vendor");
			$vendorAddress->getResource()->setTableName('vendor_address');
			if (!$vendorAddress) {
				throw new Exception("Invalid request.", 1);
			}

			$edit->setData(['vendor' => $vendor, 'vendorAddress' => $vendorAddress]);
			
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
			$edit = $layout->createBlock('Vendor_Edit');
			if (!$id = (int)$this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			$vendor = Ccc::getModel('Vendor');
			if (!$vendor->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$vendorAddress = Ccc::getModel('Vendor');
			$vendorAddress->getResource()->setTableName('vendor_address');
			if (!$vendorAddress->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$edit->setData(['vendor' => $vendor, 'vendorAddress' => $vendorAddress]);
			
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
			$grid = $layout->createBlock('Vendor_Grid');
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

			if (!$postData1 = $this->getRequest()->getPost('vendor')) {
				throw new Exception("No data posted.", 1);
			}

			if (!$postData2 = $this->getRequest()->getPost('vendorAddress')) {
				throw new Exception("No data posted.", 1);
			}

			$vendor = Ccc::getModel('Vendor');
			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$vendor->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}

				$vendor->updated_at = date('Y-m-d H:i:s');
			}
			else{
				$vendor->created_at = date('Y-m-d H:i:s');
			}

			$vendor->setData($postData1);
			if (!$vendor->save()) {
				throw new Exception("Unable to save vendor", 1);
			}

			$vendorAddress = Ccc::getModel('Vendor');
			$vendorAddress->getResource()->setTableName('vendor_address');
			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$vendorAddress->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}
			}
			else{
				$vendorAddress->vendor_id = $vendor->vendor_id;
				$vendorAddress->getResource()->setPrimaryKey('address_id');
			}

			$vendorAddress->setData($postData2);
			if (!$vendorAddress->save()) {
				throw new Exception("Unable to save vendorAddress", 1);
			}

			$this->getMessage()->addMessage('Vendor saved successfully.');
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

			if (!$vendor = Ccc::getModel('Vendor')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			if (!$vendor->delete()){
				throw new Exception("Unable to delete vendor.", 1);
			}

			$this->getMessage()->addMessage("Vendor deleted successfully.");
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
		$this->redirect('grid', null, null, true);
	}
}