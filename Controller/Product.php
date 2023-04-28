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
			echo $layout->toHtml();
		} catch (Exception $e) {
			
		}
	}

	public function exportAction()
	{
		@header('Content-Type: text/csv; charset=utf-8');  
      	@header('Content-Disposition: attachment; filename=data.csv');  
      	$output = fopen("php://output", "w");  
      	fputcsv($output, array('product_id', 'name', 'sku', 'cost', 'price', 'quantity', 'description', 'status', 'color', 'material', 'small_id', 'thumb_id', 'base_id', 'created_at', 'updated_at'));  
      	$query = "SELECT * from product ORDER BY product_id DESC";  
      	$result = Ccc::getModel('Product')->getResource()->getAdapter()->query($query); 
      	foreach($result as $row)
     	{  
           fputcsv($output, $row);  
      	}  
      	fclose($output); 
	}

	public function importAction()
	{
		$layout = $this->getLayout();
		$indexBlock = $layout->createBlock('Core_Template')->setTemplate('product/import.phtml');
		$layout->getChild('content')->addChild('index', $indexBlock);
		echo $layout->toHtml();
	}

	public function saveImportAction()
	{
		try {
			$upload = Ccc::getModel('Core_File_Upload')->setPath($_FILES['file']['full_path'])->setFile('file');
			$rows = Ccc::getModel('Core_File_Csv')->setFileName($upload->getFileName())->setPath($upload->getFileName())->read()->getRows();

			foreach ($rows as $key => &$array) {
				unset($array['product_id']);
				unset($array['created_at']);
				unset($array['updated_at']);
			}

			$product = Ccc::getModel('Product');
			foreach ($rows as $key => $row) {
				$uniqueColumns = ['sku' => $row['sku']];
				$product->getResource()->insertUpdateOnDuplicate($row, $uniqueColumns);
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
			echo json_encode(['html' => $addHtml, 'element' => 'content-grid']);
			@header('Content-type: application/json');
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
			echo json_encode(['html' => $edit, 'element' => 'content-grid']);
			@header('Content-type: application/json');
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
			echo json_encode(['html' => $gridHtml, 'element' => 'content-grid']);
			@header('Content-type: application/json');
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

			$layout = $this->getLayout();
			$gridHtml = $layout->createBlock('Product_Grid')->toHtml();
			@header('Content-type: application/json');
			echo json_encode(['html' => $gridHtml, 'element' => 'content-grid', 'message' => "Product saved successfully."]);
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

			$layout = $this->getLayout();
			$gridHtml = $layout->createBlock('Product_Grid')->toHtml();
			@header('Content-type: application/json');
			echo json_encode(['html' => $gridHtml, 'element' => 'content-grid', 'message' => "Product deleted successfully."]);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}
}