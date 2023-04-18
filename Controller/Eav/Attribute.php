<?php 

class Controller_Eav_Attribute extends Controller_Core_Action
{
	public function gridAction()
	{
		try {
			$layout = $this->getLayout();
			$grid = $layout->createBlock('Eav_Attribute_Grid');
			$layout->getChild('content')->addChild('grid', $grid);
			$layout->render();
		} catch (Exception $e) {
			
		}
	}

	public function addAction()
	{
		try {
			$layout = $this->getLayout();
			$edit = $layout->createBlock('Eav_Attribute_Edit');
			$attribute = Ccc::getModel('Eav_Attribute');
			$options = Ccc::getModel('Eav_Attribute')->getCollection();
			$edit->setData(['attribute' => $attribute, 'options' => $options]);
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
			if (!($id = (int)$this->getRequest()->getParam('id'))) {
				throw new Exception("Invalid request.", 1);
			}

			if (!($attribute = Ccc::getModel('Eav_Attribute')->load($id))) {
				throw new Exception("Invalid Id.", 1);
			}
			
			$query = "SELECT * FROM `eav_attribute_option` WHERE  `attribute_id` = '{$id}' ORDER BY `position`";
			$options = Ccc::getModel('Eav_Attribute')->fetchAll($query);
			$edit = $layout->createBlock('Eav_Attribute_Edit')->setData(['attribute' => $attribute, 'options' => $options]);

			$layout->getChild('content')->addChild('edit', $edit);
			$layout->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('grid', null, null, true);
		}
	}

	public function saveAction()
	{
		try {
			if (!$this->getRequest()->isPost()) {		
				throw new Exception("Invalid request.", 1);
			}

			if (!$postData = $this->getRequest()->getPost('attribute')) {
				throw new Exception("No data posted.", 1);
			}
			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$attribute = Ccc::getModel('Eav_Attribute')->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}
			}
			else{
				$attribute = Ccc::getModel('Eav_Attribute');
			}

			$attribute->setData($postData);
			if (!$attribute->save()) {
				throw new Exception("Unable to save attribute", 1);
			}

			if (!$options = $this->getRequest()->getPost('option')){
				$options = [];
			}
			else{
				if (!array_key_exists('exist', $options)) {
					$options['exist'] = [];
				}
			}	

			if (!$positions = $this->getRequest()->getPost('position')){
				$positions = [];
			}
			else{
				if (!array_key_exists('exist', $positions)) {
					$positions['exist'] = [];
				}
			}			

			$optionsObj = Ccc::getModel('Eav_Attribute_Option');
			$query = "SELECT * FROM `eav_attribute_option` WHERE  `attribute_id` = '{$id}'";
			if ($data = $optionsObj->fetchAll($query)){
				foreach ($data->getData() as $row) {
					if (!array_key_exists($row->option_id, $options['exist'])) {
						$row->setData(['option_id' => $row->option_id]);
						if (!$row->delete()) {
							throw new Exception("Unable to delete data.", 1);
						}
					}
				}
			}

			
			if ($options){
				if (array_key_exists('exist', $options)) {
					if ($exist = $options['exist']) {
						foreach ($exist as $optionId => $name) {
							$attributeOption = Ccc::getModel('Eav_Attribute_Option');
							$attributeOption->setData(['option_id' => $optionId, 'name' => $exist[$optionId]]);
							if (!$attributeOption->save()) {
								throw new Exception("Unable to update data.", 1);
							}
						}
					}
				}
				
				if (array_key_exists('new', $options)) {
					if ($newOption = $options['new']) {
						$newPosition = $positions['new'];
						foreach ($newOption as $optionId => $name) {
							$attributeOption = Ccc::getModel('Eav_Attribute_Option');
							$attributeOption->setData(['attribute_id' => $attribute->attribute_id,'name' => $newOption[$optionId], 'position' => $newPosition[$optionId]]);
							if (!$attributeOption->save()) {
								throw new Exception("Unable to insert data.", 1);
							}
						}
					}				
				}
			}

			if ($positions){
				if (array_key_exists('exist', $positions)) {
					if ($exist = $positions['exist']) {
						foreach ($exist as $optionId => $position) {
							$attributeOption = Ccc::getModel('Eav_Attribute_Option');
							$attributeOption->setData(['option_id' => $optionId, 'position' => $exist[$optionId]]);
							if (!$attributeOption->save()) {
								throw new Exception("Unable to update data.", 1);
							}
						}
					}
				}
			}

			$this->getMessage()->addMessage('Attribute saved successfully.');
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
		}
		$this->redirect('grid', null, null, true);
	}


	public function deleteAction()
	{
		try {
			if (!($id = (int)$this->getRequest()->getParam('id'))) {
				throw new Exception("Invalid request.", 1);
			}

			if (!($attribute = Ccc::getModel('Eav_Attribute')->load($id))) {
				throw new Exception("Invalid Id.", 1);
			}

			if(!$attribute->delete()){
				throw new Exception("Unable to delete attribute", 1);
			}

			$this->getMessage()->addMessage("Attribute deleted successfully.");
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
		$this->redirect('grid', null, null, true);
	}
}