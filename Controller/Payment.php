<?php

class Controller_Payment extends Controller_Core_Action
{

	public function indexAction()
	{
		try { 
			$layout = $this->getLayout();
			$this->_setTitle('Manage payments');
			$indexBlock = $layout->createBlock('Core_Template')->setTemplate('payment/index.phtml');
			$layout->getChild('content')->addChild('index', $indexBlock);
			$this->renderLayout();
		} catch (Exception $e) {
			
		}
	}

	public function addAction()
	{
		try {
			$layout = $this->getLayout();
			if (!$payment = Ccc::getModel('Payment')) {
				throw new Exception("Invalid request.", 1);
			}

			$addHtml = $layout->createBlock('Payment_Edit')->setData(['payment' => $payment])->toHtml();
			$this->getResponse()->jsonResponse(['html' => $addHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}

	public function editAction()
	{
		try {
			$layout = $this->getLayout();
			if (!$id = (int)$this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$payment = Ccc::getModel('Payment')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$editHtml = $layout->createBlock('Payment_Edit')->setData(['payment' => $payment])->toHtml();
			$this->getResponse()->jsonResponse(['html' => $editHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}

	public function gridAction()
	{
		try {
			$layout = $this->getLayout();
			$gridHtml = $layout->createBlock('Payment_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
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

			if (!$postData = $this->getRequest()->getPost('payment')) {
				throw new Exception("No data posted.", 1);
			}

			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$payment = Ccc::getModel('Payment')->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}
				$payment->updated_at = date('Y-m-d H:i:s');
			}
			else{
				$payment = Ccc::getModel('Payment');
				$payment->created_at = date('Y-m-d H:i:s');
			}

			$payment->setData($postData);

			if (!$payment->save()) {
				throw new Exception("Unable to save payment_method", 1);
			}

			if ($attributes = $this->getRequest()->getPost('attribute')) {
				foreach($attributes as $backendType => $value){
					foreach ($value as $attributeId => $v) {
						if (is_array($v)) {
							$v = implode(",", $v);
						}

						$model = Ccc::getModel('Core_Table');
						$model->getResource()->setTableName("payment_{$backendType}")->setPrimaryKey('value_id');
						$arrayData = ['entity_id' => $payment->getId(),'attribute_id' => $attributeId,'value' => $v];
						$uniqueColumns = ['value' => $v];
						if (!$result = $model->getResource()->insertUpdateOnDuplicate($arrayData, $uniqueColumns)) {
							throw new Exception("Unable to save product_{backendType}", 1);
						}
					}
				}
			}
			$this->getMessage()->addMessage("Payment_method saved successfully.");
			
			$gridHtml = $this->getLayout()->createBlock('Payment_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}


	public function deleteAction()
	{
		try {
			if (!$id = (int)$this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$payment = Ccc::getModel('Payment')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			if(!$payment->delete()){
				throw new Exception("Unable to delete payment_method", 1);
			}
			$this->getMessage()->addMessage("Payment_method deleted successfully.");

			$layout = $this->getLayout();
			$gridHtml = $layout->createBlock('Payment_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}
}