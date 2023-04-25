<?php

class Controller_Shipping extends Controller_Core_Action
{

	public function addAction()
	{
		try {
			$layout = $this->getLayout();
			$edit = $layout->createBlock('Shipping_Edit');
			if (!$shipping = Ccc::getModel('Shipping')) {
				throw new Exception("Invalid request.", 1);
			}

			$edit = $edit->setData(['shipping' => $shipping])->toHtml();
			echo json_encode(['html' => $edit, 'element' => 'content-grid']);
			header('Content-type: application/json');
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}


	public function editAction()
	{
		try {
			$layout = $this->getLayout();
			$edit = $layout->createBlock('Shipping_Edit');
			if (!$id = (int)$this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$shipping = Ccc::getModel('Shipping')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$edit = $edit->setData(['shipping' => $shipping])->toHtml();
			echo json_encode(['html' => $edit, 'element' => 'content-grid']);
			header('Content-type: application/json');
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}

	public function gridAction()
	{
		try {
			$layout = $this->getLayout();
			$gridHtml = $layout->createBlock('Shipping_Grid')->toHtml();
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

			if (!$postData = $this->getRequest()->getPost('shipping')) {
				throw new Exception("No data posted.", 1);
			}

			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$shipping = Ccc::getModel('Shipping')->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}
				$shipping->updated_at = date('Y-m-d H:i:s');
			}
			else{
				$shipping = Ccc::getModel('Shipping');
				$shipping->created_at = date('Y-m-d H:i:s');
			}

			$shipping->setData($postData);

			if (!$shipping->save()) {
				throw new Exception("Unable to save Shipping_method", 1);
			}


			if ($attributes = $this->getRequest()->getPost('attribute')) {
				foreach($attributes as $backendType => $value){
					foreach ($value as $attributeId => $v) {
						if (is_array($v)) {
							$v = implode(",", $v);
						}

						$model = Ccc::getModel('Core_Table');
						$model->getResource()->setTableName("shipping_{$backendType}")->setPrimaryKey('value_id');
						$arrayData = ['entity_id' => $shipping->getId(),'attribute_id' => $attributeId,'value' => $v];
						$uniqueColumns = ['value' => $v];
						if (!$result = $model->getResource()->insertUpdateOnDuplicate($arrayData, $uniqueColumns)) {
							throw new Exception("Unable to save product_{backendType}", 1);
						}
					}
				}
			}
			$this->getMessage()->addMessage("Shipping_method saved successfully.");

			$layout = $this->getLayout();
			$gridHtml = $layout->createBlock('Shipping_Grid')->toHtml();
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

			if (!$shipping = Ccc::getModel('Shipping')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			if(!$shipping->delete()){
				throw new Exception("Unable to delete payment_method", 1);
			}

			$this->getMessage()->addMessage("Shipping_method deleted successfully.");
			$layout = $this->getLayout();
			$gridHtml = $layout->createBlock('Shipping_Grid')->toHtml();

			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
			
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}
}