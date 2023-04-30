<?php

class Controller_Shipping extends Controller_Core_Action
{
	public function indexAction()
	{
		try { 
			$layout = $this->getLayout();
			$this->_setTitle('Manage Shippings');
			$indexBlock = $layout->createBlock('Core_Template')->setTemplate('shipping/index.phtml');
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

      	$shipping = Ccc::getModel('Shipping');
      	if ($query = $this->buildEavAttributeQuery($shipping)) {
      		$query = $query;
      	}
      	else{
      		$query = "SELECT * from `shipping_method` ORDER BY `shipping_method_id` DESC";  
      	}
      	
      	$result = $shipping->getResource()->fetchAll($query);
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
        $importBlock = $layout->createBlock('Core_Template')->setTemplate('shipping/import.phtml');
        $layout->getChild('content')->addChild('import', $importBlock);
        $this->renderLayout();
    }

    public function saveImportAction()
	{
		try {
			$upload = Ccc::getModel('Core_File_Upload')->setPath($_FILES['file']['full_path'])->setFile('file');
			$rows = Ccc::getModel('Core_File_Csv')->setFileName($upload->getFileName())->setPath($upload->getFileName())->read()->getRows();

			$shipping = Ccc::getModel('Shipping');
			$attributes = [];
			foreach ($rows as $key => &$row) {
				foreach (array_keys($row) as $value) {
					$query = "SHOW COLUMNS FROM `shipping_method` LIKE '".$value."'";
					$result = Ccc::getModel('Shipping')->getResource()->getAdapter()->query($query);
					if ($result->num_rows == 0) {
						$attributes[$row['shipping_method_id']][$value] = $row[$value]; 
						unset($row[$value]);  
					}
				}
			}

			foreach ($rows as $key => $array) {
	      		unset($array['shipping_method_id']);
	      		$array['status'] = ($array['status'] == 'Active') ? 1 : 2;
				$uniqueColumns = ['name' => $array['name']];
				$shipping->getResource()->insertUpdateOnDuplicate($array, $uniqueColumns);
			}

			if ($attributes) {
				foreach ($attributes as $shippingId => $attributeArray) {
					if ($shipping->load($shippingId)) {
						foreach ($attributeArray as $key => $value) {
							$attribute = Ccc::getModel('Eav_Attribute')->fetchRow("SELECT * FROM `eav_attribute` WHERE `entity_type_id` = 8 AND `code` = '{$key}'");
							$model = Ccc::getModel('Core_Table');
							$model->getResource()->setTableName("shipping_{$attribute->backend_type}")->setPrimaryKey('value_id');
							$arrayData = ['entity_id' => $shippingId,'attribute_id' => $attribute->attribute_id,'value' => $value];
							$uniqueColumns = ['value' => $value];
							if (!$result = $model->getResource()->insertUpdateOnDuplicate($arrayData, $uniqueColumns)) {
								throw new Exception("Unable to save shipping_{$attribute->backend_type}", 1);
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
			$edit = $layout->createBlock('Shipping_Edit');
			if (!$shipping = Ccc::getModel('Shipping')) {
				throw new Exception("Invalid request.", 1);
			}

			$edit = $edit->setData(['shipping' => $shipping])->toHtml();
			$this->getResponse()->jsonResponse(['html' => $edit, 'element' => 'content-grid']);
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
			$this->getResponse()->jsonResponse(['html' => $edit, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}

	public function gridAction()
	{
		try {
			$currentPage = $this->getRequest()->getPost('p',1);
            $recordPerPage = $this->getRequest()->getPost('rpp',10);
            $layout = $this->getLayout();
            $gridHtml = $layout->createBlock('Shipping_Grid');
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
			
			$gridHtml = $this->getLayout()->createBlock('Shipping_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}
}