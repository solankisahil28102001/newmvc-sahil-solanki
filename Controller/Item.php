<?php 

class Controller_Item extends Controller_Core_Action
{
	public function indexAction()
    {
        try { 
            $this->_setTitle('Manage Items');
            $indexBlock = $this->getLayout()->createBlock('Core_Template')->setTemplate('item/index.phtml');
            $this->getLayout()->getChild('content')->addChild('index', $indexBlock);
            echo $this->getLayout()->toHtml();
        } catch (Exception $e) {
            
        }
    }

	public function gridAction()
	{
		$gridHtml = $this->getLayout()->createBlock('Item_Grid')->toHtml();
		echo json_encode(['html' => $gridHtml, 'element' => 'content-grid']);
        header('Content-type: application/json');
	}

	public function editAction()
	{
		try {
			if (!$id = $this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request", 1);
			}

			if (!$item = Ccc::getModel('Item')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$editHtml = $this->getLayout()->createBlock('Item_Edit')->setData(['item' => $item])->toHtml();
			echo json_encode(['html' => $editHtml, 'element' => 'content-grid']);
        	@header('Content-type: application/json');
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);			
			$this->redirect('index', null, null, true);
		}
	}

	public function addAction()
	{
		try {
			$item = Ccc::getModel('Item');

			$addHtml = $this->getLayout()->createBlock('Item_Edit')->setData(['item' => $item])->toHtml();
			echo json_encode(['html' => $addHtml, 'element' => 'content-grid']);
        	@header('Content-type: application/json');
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);			
			$this->redirect('index', null, null, true);
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

			$gridHtml = $this->getLayout()->createBlock('Item_Grid')->toHtml();
			echo json_encode(['html' => $gridHtml, 'element' => 'content-grid', 'message' => 'Item deleted successfully.']);
	        header('Content-type: application/json');
			// $this->getMessage()->addMessage('Item deleted successfully.');

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
			$gridHtml = $this->getLayout()->createBlock('Item_Grid')->toHtml();
			echo json_encode(['html' => $gridHtml, 'element' => 'content-grid', 'message' => 'Item saved successfully.']);
	        header('Content-type: application/json');
			// $this->getMessage()->addMessage("Item saved successfully.");
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}
}