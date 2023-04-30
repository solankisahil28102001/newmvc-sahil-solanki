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

	public function exportAction()
	{
		@header('Content-Type: text/csv; charset=utf-8');  
      	@header('Content-Disposition: attachment; filename=data.csv');  
      	$output = fopen("php://output", "w");  

      	$category = Ccc::getModel('Category');
      	if ($query = $this->buildEavAttributeQuery($category)) {
      		$query = $query;
      	}
      	else{
      		$query = "SELECT * from `category` ORDER BY `category_id` DESC";  
      	}
      	$result = $category->getResource()->fetchAll($query);
      	$header = [];
      	if ($result) {
            foreach($result as &$row)
            {  
	      		unset($row['created_at']);
				unset($row['updated_at']);
				if (array_key_exists('status', $row)) {
					$row['status'] = ($row['status'] == 1) ? 'Active' : 'Inactive';
				}
                if (!$header) {
                    $header = array_keys($row);
                    fputcsv($output, $header);
                }
               fputcsv($output, $row);  
            }  
        }
      	fclose($output);  
	}

	public function importAction()
    {
        $layout = $this->getLayout();
        $importBlock = $layout->createBlock('Core_Template')->setTemplate('category/import.phtml');
        $layout->getChild('content')->addChild('import', $importBlock);
        $this->renderLayout();
    }

    public function saveImportAction()
	{
		try {
			$upload = Ccc::getModel('Core_File_Upload')->setPath($_FILES['file']['full_path'])->setFile('file');
			$rows = Ccc::getModel('Core_File_Csv')->setFileName($upload->getFileName())->setPath($upload->getFileName())->read()->getRows();

			$category = Ccc::getModel('Category');
			$attributes = [];
			foreach ($rows as $key => &$row) {
				foreach (array_keys($row) as $value) {
					$query = "SHOW COLUMNS FROM `category` LIKE '".$value."'";
					$result = Ccc::getModel('Category')->getResource()->getAdapter()->query($query);
					if ($result->num_rows == 0) {
						$attributes[$row['category_id']][$value] = $row[$value]; 
						unset($row[$value]);  
					}
				}
			}

			foreach ($rows as $key => $array) {
	      		unset($array['category_id']);
	      		$array['status'] = ($array['status'] == 'Active') ? 1 : 2;
				$uniqueColumns = ['path' => $array['path']];
				$category->getResource()->insertUpdateOnDuplicate($array, $uniqueColumns);
			}

			if ($attributes) {
				foreach ($attributes as $categoryId => $attributeArray) {
					if ($category->load($categoryId)) {
						foreach ($attributeArray as $key => $value) {
							$attribute = Ccc::getModel('Eav_Attribute')->fetchRow("SELECT * FROM `eav_attribute` WHERE `entity_type_id` = 6 AND `code` = '{$key}'");
							$model = Ccc::getModel('Core_Table');
							$model->getResource()->setTableName("category_{$attribute->backend_type}")->setPrimaryKey('value_id');
							$arrayData = ['entity_id' => $categoryId,'attribute_id' => $attribute->attribute_id,'value' => $value];
							$uniqueColumns = ['value' => $value];
							if (!$result = $model->getResource()->insertUpdateOnDuplicate($arrayData, $uniqueColumns)) {
								throw new Exception("Unable to save category_{$attribute->backend_type}", 1);
							}
						}
					}
				}
			}
			$this->getMessage()->addMessage("Data inserted successfully.");
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
		//$this->redirect('index');
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