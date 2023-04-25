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
            echo $layout->toHtml();
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
			echo json_encode(['html' => $addHtml, 'element' => 'content-grid']);
            header('Content-type: application/json');
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
			echo json_encode(['html' => $editHtml, 'element' => 'content-grid']);
            header('Content-type: application/json');
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}

	public function gridAction()
	{
		try {
			$gridHtml = $this->getLayout()->createBlock('Brand_Grid')->toHtml();
            echo json_encode(['html' => $gridHtml, 'element' => 'content-grid']);
            header('Content-type: application/json');
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

			$gridHtml = $this->getLayout()->createBlock('Brand_Grid')->toHtml();
            echo json_encode(['html' => $gridHtml, 'element' => 'content-grid', 'message' => 'Brand saved successfully.']);
            header('Content-type: application/json');

			// $this->getMessage()->addMessage('Brand saved successfully.');
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

			$gridHtml = $this->getLayout()->createBlock('Brand_Grid')->toHtml();
            echo json_encode(['html' => $gridHtml, 'element' => 'content-grid', 'message' => 'Brand deleted successfully.']);
            header('Content-type: application/json');
			// $this->getMessage()->addMessage("Brand deleted successfully.");
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}
}