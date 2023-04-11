<?php

class Controller_Payment extends Controller_Core_Action
{
	public function addAction()
	{
		try {
			$layout = $this->getLayout();
			$edit = $layout->createBlock('Payment_Edit');
			if (!$payment = Ccc::getModel('Payment')) {
				throw new Exception("Invalid request.", 1);
			}

			$edit->setData(['payment' => $payment]);
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
			$edit = $layout->createBlock('Payment_Edit');
			if (!$id = (int)$this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$payment = Ccc::getModel('Payment')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$edit->setData(['payment' => $payment]);
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
			$grid = $layout->createBlock('Payment_Grid');
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

			$this->getMessage()->addMessage('Payment_method saved successfully.');
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

			if (!$payment = Ccc::getModel('Payment')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			if(!$payment->delete()){
				throw new Exception("Unable to delete payment_method", 1);
			}

			$this->getMessage()->addMessage("Payment_method deleted successfully.");
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
		$this->redirect('grid', null, null, true);
	}
}