<?php

class Controller_Salesman_Price extends Controller_Core_Action
{

	public function gridAction()
	{
		try {
			if (!$id = (int)$this->getRequest()->getParam('id')){
				throw new Exception("Invalid request.", 1);
			}

			$gridHtml = $this->getLayout()->createBlock('Salesman_Price_Grid');
			$gridHtml->setSalesmanId($id);
			$gridHtml = $gridHtml->toHtml();
			echo json_encode(['html' => $gridHtml, 'element' => 'content-grid']);
       		@header('Content-type: application/json');
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
	}

	public function operationAction()
	{
		try {
			if (!$this->getRequest()->isPost()) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$id = $this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$operation = $this->getRequest()->getParam('submit')) {
				throw new Exception("Invalid request.", 1);
			}

			if ($operation == 'Update') {
				if (!$prices = $this->getRequest()->getPost('salesman_prices')){
					throw new Exception("Invalid request.", 1);
				}

				foreach ($prices as $key => $value) {
					$query = "SELECT * FROM `salesman_price` WHERE `product_id` = {$key} AND `salesman_id` = {$id}";
					$data = Ccc::getModel('Salesman_Price')->fetchRow($query);
					if ($value == '' && $data != false) {
						$salesmanPrice = Ccc::getModel('Salesman_Price')->setData(['entity_id'=>$data->entity_id]);
						$result = $salesmanPrice->delete();
						if ($result == false) {
							throw new Exception("Deletion failed.", 1);
						}
					}

					elseif ($value != '' && $data == false) {
						$salesmanPrice = Ccc::getModel('Salesman_Price')->setData(['product_id'=>$key,'salesman_id'=>$id,'salesman_price'=>$value]);
						$insert_id = $salesmanPrice->save();

						if (!$insert_id) {
							throw new Exception("Insertion failed.", 1);
						}

						$this->getMessage()->addMessage("Data inserted successfully.");
					}
					elseif ($value != '' && $data != false) {
						$data->setData(['salesman_price'=>$value, 'entity_id'=>$data->entity_id]);
						$result = $data->save();
						if($result == false){
							throw new Exception("Updation failed.", 1);
						}

						$this->getMessage()->addMessage("Data updated successfully.");
					}
				}
			}

			elseif ($operation == 'Delete') {
				$delete = (array_key_exists('remove', $this->getRequest()->getPost())) ? $this->getRequest()->getPost('remove') : [];
				$strDelete = implode("",$delete);
				if ($strDelete == '') {
					$this->redirect('grid');
				}

				$salesmanPrice = Ccc::getModel('Salesman_Price')->load($delete);
				$salesmanPrice->entity_id = $delete;
				if (!$result = $salesmanPrice->delete()) {
					throw new Exception("Deletion failed", 1);
				}

				$this->getMessage()->addMessage("Data deleted successfully.");
			}
			else{
				throw new Exception("Invalid operation", 1);
			}
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
		$this->gridAction();	
	}
}