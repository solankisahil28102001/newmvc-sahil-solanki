<?php

class Controller_Category extends Controller_Core_Action
{
	public function indexAction()
	{
		try { 
			$layout = $this->getLayout();
			$this->_setTitle('Manage Categories');
			$indexBlock = $layout->createBlock('Core_Template')->setTemplate('category/index.phtml');
			$layout->getChild('content')->addChild('index', $indexBlock);
			$this->renderLayout();
		} catch (Exception $e) {
			
		}
	}

	public function gridAction()
	{
		try {
			$currentPage = $this->getRequest()->getPost('p',1);
            $recordPerPage = $this->getRequest()->getPost('rpp',10);
            $layout = $this->getLayout();
            $gridHtml = $layout->createBlock('Category_Grid');
            $gridHtml->setCurrentPage($currentPage)->setRecordPerPage($recordPerPage);
            $gridHtml = $gridHtml->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}


	public function addAction()
	{
		try {
			$category = Ccc::getModel('Category');
			$pathCategories = $category->preparePathCategories();
			
			$layout = $this->getLayout();
			$addHtml = $layout->createBlock('Category_Edit')
				->setData(['category' => $category,'pathCategories' => $pathCategories])
				->toHtml();

			$this->getResponse()->jsonResponse(['html' => $addHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
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
			$edit = $edit->toHtml();
			$this->getResponse()->jsonResponse(['html' => $edit, 'element' => 'content-grid']);

		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
			$this->redirect('index');
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
			$this->getMessage()->addMessage("Category saved successfully.");

			$gridHtml = $this->getLayout()->createBlock('Category_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} 
		catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
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
			if (!$category->delete()) {
                throw new Exception("Unable to delete category.", 1);
            }
            $this->getMessage()->addMessage("Category deleted successfully.");

			$gridHtml = $this->getLayout()->createBlock('Category_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}

}