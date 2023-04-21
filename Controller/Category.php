<?php

class Controller_Category extends Controller_Core_Action
{
	public function indexAction()
	{
		try {
			$layout = $this->getLayout();
			$index = $layout->createBlock('Category_Index');
			$layout->getChild('content')->addChild('index', $index);
			$layout->render();
		} catch (Exception $e) {
			
		}
	}

	public function gridAction()
	{
		try {
			$layout = $this->getLayout();
			$grid = $layout->createBlock('Category_Grid');
			$layout->getChild('content')->addChild('grid', $grid);
			echo $grid->toHtml();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
	}


	public function addAction()
	{
		try {
			$layout = $this->getLayout();
			$edit = $layout->createBlock('Category_Edit');

			$category = Ccc::getModel('Category');
			$pathCategories = $category->preparePathCategories();
			$edit->setData(['category' => $category,'pathCategories' => $pathCategories]);

			$layout->getChild('content')->addChild('edit', $edit);
			echo $edit->toHtml();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('grid');
		}
	}


	public function editAction()
	{
		try {
			$layout = $this->getLayout();
			$edit = $layout->createBlock('Category_Edit');
			if (!$id = (int)$this->getRequest()->getParam('id')){
				throw new Exception("Invalid Request.", 1);
			}

			if (!$category = Ccc::getModel('Category')->load($id)) {
				throw new Exception("Invalid id.", 1);
			}

			$pathCategories = $category->showParents();
			$edit->setData(['category' => $category,'pathCategories' => $pathCategories]);

			$layout->getChild('content')->addChild('edit', $edit);
			echo $edit->toHtml();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
			$this->redirect('grid',null,[],true);
		}
	}

	public function saveAction()
	{
		try {
			if (!$this->getRequest()->getPost()) {
				throw new Exception("Data not posted.", 1);
			}

			if (!$postData = $this->getRequest()->getPost('category')) {
				throw new Exception("No data posted.", 1);
			}

			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$category = Ccc::getModel('Category')->load($id)) {
					throw new Exception("Invalid ID", 1);
				}
				$category->updated_at = date("Y-m-d H:i:s");
			}
			else{
				$category = Ccc::getModel('Category');
				$category->created_at = date("Y-m-d H:i:s");
			}

			$category->setData($postData);

 			if (!$category->save()) {
                throw new Exception("Unable to save category.", 1);
            }

            $category->updatePath();
            if ($attributeData = $this->getRequest()->getPost('attribute')) {
				foreach ($attributeData as $backendType => $value) {
					foreach ($value as $attributeId => $v) {
						if ($v) {
							if (is_array($v)) {
								$v = implode(",", $v);
							}

							$model = Ccc::getModel('Core_Table');
							$resource = $model->getResource()->setTableName("category_{$backendType}")->setPrimaryKey("value_id");
							
							$arrayData = ['entity_id' => $category->getId(), 'attribute_id' => $attributeId, 'value' => $v];
							$uniqueColumns = ['value' => $v];
							if (!$id = $model->getResource()->insertUpdateOnDuplicate($arrayData, $uniqueColumns)){
								throw new Exception("Unable to save category_{$backendType}", 1);
							}
						}
					}
				}
			}
            $this->getMessage()->addMessage("Data saved successfully.");
		} 
		catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
		}
		$this->redirect('index',null,[],true);
	}


	public function deleteAction()
	{
		try {
			if (!$id = (int)$this->getRequest()->getParam('id')){
				throw new Exception("Invalid Request.", 1);
			}

			if (!$category = Ccc::getModel('Category')->load($id)) {
				throw new Exception("Invalid id.", 1);
			}

			$query = "DELETE FROM `category` WHERE `path` LIKE '{$category->path}-%'";
			$category->getResource()->getAdapter()->delete($query);
			$category->delete();
			$this->getMessage()->addMessage("Category deleted successfully.");
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
		}
		$this->redirect('grid',null,[],true);
	}

}