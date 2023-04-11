<?php

class Controller_Salesman extends Controller_Core_Action
{
	public function addAction()
	{
		try {
			$layout = $this->getLayout();
			$edit = $layout->createBlock('Salesman_Edit');
			$salesman = Ccc::getModel('Salesman');		
			if (!$salesman) {
				throw new Exception("Invalid request.", 1);
			}

			$salesmanAddress = Ccc::getModel("Salesman");
			$salesmanAddress->getResource()->setTableName('salesman_address');
			if (!$salesmanAddress) {
				throw new Exception("Invalid request.", 1);
			}
			$edit->setData(['salesman' => $salesman, 'salesmanAddress' => $salesmanAddress]);
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
			$edit = $layout->createBlock('Salesman_Edit');
			
			if (!$id = (int)$this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			$salesman = Ccc::getModel('Salesman');
			if (!$salesman->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$salesmanAddress = Ccc::getModel('Salesman');
			$salesmanAddress->getResource()->setTableName('salesman_address');
			if (!$salesmanAddress->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$edit->setData(['salesman' => $salesman, 'salesmanAddress' => $salesmanAddress]);
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
			$grid = $layout->createBlock('Salesman_Grid');
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

			if (!$postData1 = $this->getRequest()->getPost('salesman')) {
				throw new Exception("No data posted.", 1);
			}

			if (!$postData2 = $this->getRequest()->getPost('salesmanAddress')) {
				throw new Exception("No data posted.", 1);
			}

			$salesman = Ccc::getModel('Salesman');
			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$salesman->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}

				$salesman->updated_at = date('Y-m-d H:i:s');
			}
			else{
				$salesman->created_at = date('Y-m-d H:i:s');
			}

			$salesman->setData($postData1);
			if (!$salesman->save()) {
				throw new Exception("Unable to save salesman", 1);
			}

			$salesmanAddress = Ccc::getModel('Salesman');
			$salesmanAddress->getResource()->setTableName('salesman_address');
			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$salesmanAddress->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}
			}
			else{
				$salesmanAddress->salesman_id = $salesman->salesman_id;
				$salesmanAddress->getResource()->setPrimaryKey('address_id');
			}

			$salesmanAddress->setData($postData2);
			if (!$salesmanAddress->save()) {
				throw new Exception("Unable to save salesmanAddress", 1);
			}

			$this->getMessage()->addMessage('Salesman saved successfully.');
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

			if (!$salesman = Ccc::getModel('Salesman')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			if (!$salesman->delete()){
				throw new Exception("Unable to delete salesman.", 1);
			}

			$this->getMessage()->addMessage("Salesman deleted successfully.");
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
		$this->redirect('grid', null, null, true);
	}
	
}

?>