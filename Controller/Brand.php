<?php

class Controller_Brand extends Controller_Core_Action
{
	public function indexAction()
    {
        try { 
            $layout = $this->getLayout();
            $this->_setTitle('Manage Brands');
            $indexBlock = $layout->createBlock('Core_Template')->setTemplate('brand/index.phtml');
            $layout->getChild('content')->addChild('index', $indexBlock);
            $this->renderLayout();
        } catch (Exception $e) {
            
        }
    }

	public function addAction()
	{
		try {
			if (!$brand = Ccc::getModel('Brand')) {
				throw new Exception("Invalid request.", 1);
			}

			$addHtml = $this->getLayout()->createBlock('Brand_Edit');
			$addHtml->setRow($brand);
			$addHtml = $addHtml->toHtml();
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

			if (!$brand = Ccc::getModel('Brand')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}
			
			$editHtml = $this->getLayout()->createBlock('Brand_Edit');
			$editHtml->setRow($brand);
			$editHtml = $editHtml->toHtml();
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
            $gridHtml = $layout->createBlock('Brand_Grid');
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

			if (!$postData = $this->getRequest()->getPost('brand')) {
				throw new Exception("No data posted.", 1);
			}

			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$brand = Ccc::getModel('Brand')->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}
				$brand->updated_at = date('Y-m-d H:i:s');
			}
			else{
				$brand = Ccc::getModel('Brand');
				$brand->created_at = date('Y-m-d H:i:s');
			}

			$brand->setData($postData);

			if (!$brand->save()) {
				throw new Exception("Unable to save brand", 1);
			}
			$this->getMessage()->addMessage('Brand saved successfully.');

			$gridHtml = $this->getLayout()->createBlock('Brand_Grid')->toHtml();
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

			if (!$brand = Ccc::getModel('Brand')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			if(!$brand->delete()){
				throw new Exception("Unable to delete Brand", 1);
			}
			$this->getMessage()->addMessage("Brand deleted successfully.");

			$gridHtml = $this->getLayout()->createBlock('Brand_Grid')->toHtml();
            $this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}
}