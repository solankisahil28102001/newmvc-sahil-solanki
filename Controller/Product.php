<?php

class Controller_Product extends Controller_Core_Action
{
	public function indexAction()
	{
		try { 
			$layout = $this->getLayout();
			$this->_setTitle('Manage Products');
			$indexBlock = $layout->createBlock('Core_Template')->setTemplate('product/index.phtml');
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

      	$product = Ccc::getModel('Product');
      	if ($query = $this->buildEavAttributeQuery($product)) {
      		$query = $query;
      	}
      	else{
      		$query = "SELECT * from `product` ORDER BY `product_id` DESC";  
      	}
      	$result = $product->getResource()->fetchAll($query);
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
		$importBlock = $layout->createBlock('Core_Template')->setTemplate('product/import.phtml');
		$layout->getChild('content')->addChild('import', $importBlock);
		$this->renderLayout();
	}

	public function saveImportAction()
	{
		try {
			$upload = Ccc::getModel('Core_File_Upload')->setPath($_FILES['file']['full_path'])->setFile('file');
			$rows = Ccc::getModel('Core_File_Csv')->setFileName($upload->getFileName())->setPath($upload->getFileName())->read()->getRows();

			$product = Ccc::getModel('Product');
			$attributes = [];
			foreach ($rows as $key => &$row) {
				foreach (array_keys($row) as $value) {
					$query = "SHOW COLUMNS FROM `product` LIKE '".$value."'";
					$result = Ccc::getModel('Product')->getResource()->getAdapter()->query($query);
					if ($result->num_rows == 0) {
						$attributes[$row['product_id']][$value] = $row[$value]; 
						unset($row[$value]);  
					}
				}
			}

			foreach ($rows as $key => $array) {
	      		unset($array['product_id']);
	      		$array['status'] = ($array['status'] == 'Active') ? 1 : 2;
				$uniqueColumns = ['sku' => $array['sku']];
				$product->getResource()->insertUpdateOnDuplicate($array, $uniqueColumns);
			}

			if ($attributes) {
				foreach ($attributes as $productId => $attributeArray) {
					foreach ($attributeArray as $key => $value) {
						if ($product->load($productId)) {
							$attribute = Ccc::getModel('Eav_Attribute')->fetchRow("SELECT * FROM `eav_attribute` WHERE `entity_type_id` = 2 AND `code` = '{$key}'");
							$model = Ccc::getModel('Core_Table');
							$model->getResource()->setTableName("product_{$attribute->backend_type}")->setPrimaryKey('value_id');
							$arrayData = ['entity_id' => $productId,'attribute_id' => $attribute->attribute_id,'value' => $value];
							$uniqueColumns = ['value' => $value];
							if (!$result = $model->getResource()->insertUpdateOnDuplicate($arrayData, $uniqueColumns)) {
								throw new Exception("Unable to save product_{$attribute->backend_type}", 1);
							}
						}
					}
				}
			}
			$this->getMessage()->addMessage("Data inserted successfully.");
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
		$this->redirect('index');
	}

	public function addAction()
	{
		try {
			$layout = $this->getLayout();
			$addHtml = $layout->createBlock('Product_Edit');
			if (!($product = Ccc::getModel('Product'))) {
				throw new Exception("Invalid request.", 1);
			}

			$addHtml = $addHtml->setData(['product' => $product])->toHtml();
			$this->getResponse()->jsonResponse(['html' => $addHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('grid', null, null, true);
		}
	}


	public function editAction()
	{	
		try {
			$layout = $this->getLayout();
			$edit = $layout->createBlock('Product_Edit');
			
			if (!($id = (int)$this->getRequest()->getParam('id'))) {
				throw new Exception("Invalid request.", 1);
			}

			if (!($product = Ccc::getModel('Product')->load($id))) {
				throw new Exception("Invalid Id.", 1);
			}
			$edit = $edit->setData(['product' => $product])->toHtml();
			$this->getResponse()->jsonResponse(['html' => $edit, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('grid', null, null, true);
		}
	}


	public function gridAction()
	{
		try {
			$currentPage = $this->getRequest()->getPost('p',1);
			$recordPerPage = $this->getRequest()->getPost('rpp',10);
			$layout = $this->getLayout();
			$gridHtml = $layout->createBlock('Product_Grid');
			$gridHtml->setCurrentPage($currentPage)->setRecordPerPage($recordPerPage);
			$gridHtml = $gridHtml->toHtml();
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

			if (!$postData = $this->getRequest()->getPost('product')) {
				throw new Exception("No data posted.", 1);
			}

			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$product = Ccc::getModel('Product')->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}
				$product->updated_at = date('Y-m-d H:i:s');
			}
			else{
				$product = Ccc::getModel('Product');
				$product->created_at = date('Y-m-d H:i:s');
			}

			$product->setData($postData);

			if (!$product->save()) {
				throw new Exception("Unable to save product", 1);
			}

			if ($attributes = $this->getRequest()->getPost('attribute')) {
				foreach($attributes as $backendType => $value){
					foreach ($value as $attributeId => $v) {
						if (is_array($v)) {
							$v = implode(",", $v);
						}

						$model = Ccc::getModel('Core_Table');
						$model->getResource()->setTableName("product_{$backendType}")->setPrimaryKey('value_id');
						$arrayData = ['entity_id' => $product->getId(),'attribute_id' => $attributeId,'value' => $v];
						$uniqueColumns = ['value' => $v];
						if (!$result = $model->getResource()->insertUpdateOnDuplicate($arrayData, $uniqueColumns)) {
							throw new Exception("Unable to save product_{backendType}", 1);
						}
					}
				}
			}
			$this->getMessage()->addMessage("Product saved successfully.");
			
			$gridHtml = $this->getLayout()->createBlock('Product_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}


	public function deleteAction()
	{
		try {
			if (!($id = (int)$this->getRequest()->getParam('id'))) {
				throw new Exception("Invalid request.", 1);
			}

			if (!($product = Ccc::getModel('Product')->load($id))) {
				throw new Exception("Invalid Id.", 1);
			}

			if(!$product->delete()){
				throw new Exception("Unable to delete product", 1);
			}
			$this->getMessage()->addMessage("Product deleted successfully.");

			$gridHtml = $this->getLayout()->createBlock('Product_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}
}