<?php

class Controller_Salesman extends Controller_Core_Action
{
	public function indexAction()
    {
        try { 
            $this->_setTitle('Manage Salesman');
            $indexBlock = $this->getLayout()->createBlock('Core_Template')->setTemplate('salesman/index.phtml');
            $this->getLayout()->getChild('content')->addChild('index', $indexBlock);
            $this->renderLayout();
        } catch (Exception $e) {
            
        }
    }

	public function addAction()
	{
		try {
			$salesman = Ccc::getModel('Salesman');		
			if (!$salesman) {
				throw new Exception("Invalid request.", 1);
			}

			$salesmanAddress = Ccc::getModel("Salesman");
			$salesmanAddress->getResource()->setTableName('salesman_address');
			if (!$salesmanAddress) {
				throw new Exception("Invalid request.", 1);
			}

			$addHtml = $this->getLayout()->createBlock('Salesman_Edit')->setData(['salesman' => $salesman, 'salesmanAddress' => $salesmanAddress])->toHtml();
			$this->getResponse()->jsonResponse(['html' => $addHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}

	public function editAction()
	{
		try {
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

			$editHtml = $this->getLayout()->createBlock('Salesman_Edit')->setData(['salesman' => $salesman, 'salesmanAddress' => $salesmanAddress])->toHtml();
			$this->getResponse()->jsonResponse(['html' => $editHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}

	public function gridAction()
	{
		try {
			$currentPage = $this->getRequest()->getPost('p',1);
            $recordPerPage = $this->getRequest()->getPost('rpp',10);
            $layout = $this->getLayout();
            $gridHtml = $layout->createBlock('Salesman_Grid');
            $gridHtml->setCurrentPage($currentPage)->setRecordPerPage($recordPerPage);
            $gridHtml = $gridHtml->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
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

			$gridHtml = $this->getLayout()->createBlock('Salesman_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
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

			$gridHtml = $this->getLayout()->createBlock('Salesman_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}
	
}

?>