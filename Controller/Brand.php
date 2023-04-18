<?php

class Controller_Brand extends Controller_Core_Action
{
	public function addAction()
	{
		try {
			$layout = $this->getLayout();
			$edit = $layout->createBlock('Brand_Edit');
			if (!$brand = Ccc::getModel('Brand')) {
				throw new Exception("Invalid request.", 1);
			}

			$edit->setRow($brand);
			$layout->getChild('content')->addChild('edit', $edit);
			echo $layout->toHtml();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('grid', null, null, true);
		}
	}

	public function editAction()
	{
		try {
			$layout = $this->getLayout();
			$edit = $layout->createBlock('Brand_Edit');
			if (!$id = (int)$this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$brand = Ccc::getModel('Brand')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}
			
			$edit->setRow($brand);
			$layout->getChild('content')->addChild('edit', $edit);
			echo $layout->toHtml();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('grid', null, null, true);
		}
	}

	public function gridAction()
	{
		try {
			$layout = $this->getLayout();
			$grid = $layout->createBlock('Brand_Grid');
			$layout->getChild('content')->addChild('grid', $grid);
			echo $layout->toHtml();
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

			if (!$brand = Ccc::getModel('Brand')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			if(!$brand->delete()){
				throw new Exception("Unable to delete Brand", 1);
			}

			$this->getMessage()->addMessage("Brand deleted successfully.");
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
		$this->redirect('grid', null, null, true);
	}
}