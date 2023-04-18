<?php 

class Controller_Item extends Controller_Core_Action
{
	
	public function gridAction()
	{
		$layout = $this->getLayout();
		$grid = $layout->createBlock('Item_Grid');
		$layout->getChild('content')->addChild('grid', $grid);
		$layout->render();
	}

	public function editAction()
	{
		try {
			$layout = $this->getLayout();
			if (!$id = $this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request", 1);
			}

			if (!$item = Ccc::getModel('Item')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$edit = $layout->createBlock('Item_Edit');
			$edit->setData(['item' => $item]);
			$layout->getChild('content')->addChild('edit', $edit);
			$layout->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);			
			$this->redirect('grid', null, null, true);			
		}
	}

	public function addAction()
	{
		try {
			$layout = $this->getLayout();
			$item = Ccc::getModel('Item');

			$edit = $layout->createBlock('Item_Edit');
			$edit->setData(['item' => $item]);
			$layout->getChild('content')->addChild('edit', $edit);
			$layout->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);			
			$this->redirect('grid', null, null, true);			
		}
	}

	public function deleteAction()
	{
		try {
			$layout = $this->getLayout();
			if (!$id = $this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request", 1);
			}

			if (!$item = Ccc::getModel('Item')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}
			if (!$item->delete()){
				throw new Exception("Unable to delete item.", 1);
			}

			$this->getMessage()->addMessage('Item deleted successfully.');

		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);			
		}
		$this->redirect('grid', null, null, true);			
	}

	public function saveAction()
	{
		try {
			if (!$this->getRequest()->isPost()) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$itemData = $this->getRequest()->getPost('item')) {
				throw new Exception("Data not posted.", 1);
			}

			if ($id = $this->getRequest()->getParam('id')) {
				if (!$item = Ccc::getModel('Item')->load($id)) {
					throw new Exception("Invalid id.", 1);
				}
				$item->updated_at = date("Y-m-d H:i:s");
			}
			else{
				$item = Ccc::getModel('Item');
				$item->created_at = date("Y-m-d H:i:s");
			}

			$item->setData($itemData);
			if (!$item->save()) {
				throw new Exception("Unable to save item", 1);
			}

			if ($attributeData = $this->getRequest()->getPost('attribute')) {
				foreach ($attributeData as $backendType => $value) {
					foreach ($value as $attributeId => $v) {
						if (is_array($v)) {
							$v = implode(",", $v);
						}

						$model = Ccc::getModel('Core_Table');
						$resource = $model->getResource()->setTableName("item_{$backendType}")->setPrimaryKey("value_id");
						
						$arrayData = ['entity_id' => $item->getId(), 'attribute_id' => $attributeId, 'value' => $v];
						$uniqueColumns = ['value' => $v];
						if (!$id = $model->getResource()->insertUpdateOnDuplicate($arrayData, $uniqueColumns)){
							throw new Exception("Unable to save item_{$backendType}", 1);
						}
					}
				}
			}
			$this->getMessage()->addMessage("Item saved successfully.");
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
		$this->redirect('grid', null, null, true);
	}
}