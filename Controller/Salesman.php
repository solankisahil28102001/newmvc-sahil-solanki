<?php

class Controller_Salesman extends Controller_Core_Action
{
	public function indexAction()
    {
        try { 
            $this->_setTitle('Manage Salesman');
            $indexBlock = $this->getLayout()->createBlock('Core_Template')->setTemplate('salesman/index.phtml');
            $this->getLayout()->getChild('content')->addChild('index', $indexBlock);
            $this->renderLayout();
        } catch (Exception $e) {
            
        }
    }

    public function exportAction()
	{
		@header('Content-Type: text/csv; charset=utf-8');  
      	@header('Content-Disposition: attachment; filename=data.csv');  
      	$output = fopen("php://output", "w");  

      	$salesman = Ccc::getModel('Salesman');
      	if ($query = $this->buildEavAttributeQuery($salesman)) {
      		$query = $query;
      	}
      	else{
      		$query = "SELECT * from `salesman` ORDER BY `salesman_id` DESC";  
      	}
      	
      	$result = $salesman->getResource()->fetchAll($query);
      	$header = [];
      	if ($result) {
            foreach($result as &$row)
            {  
	      		unset($row['created_at']);
				unset($row['updated_at']);
				unset($row['salesman_address_id']);
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
        $importBlock = $layout->createBlock('Core_Template')->setTemplate('salesman/import.phtml');
        $layout->getChild('content')->addChild('import', $importBlock);
        $this->renderLayout();
    }

    public function saveImportAction()
	{
		try {
			$upload = Ccc::getModel('Core_File_Upload')->setPath($_FILES['file']['full_path'])->setFile('file');
			$rows = Ccc::getModel('Core_File_Csv')->setFileName($upload->getFileName())->setPath($upload->getFileName())->read()->getRows();

			$salesman = Ccc::getModel('Salesman');
			$attributes = [];
			foreach ($rows as $key => &$row) {
				foreach (array_keys($row) as $value) {
					$query = "SHOW COLUMNS FROM `salesman` LIKE '".$value."'";
					$result = Ccc::getModel('Salesman')->getResource()->getAdapter()->query($query);
					if ($result->num_rows == 0) {
						$attributes[$row['salesman_id']][$value] = $row[$value]; 
						unset($row[$value]);  
					}
				}
			}

			foreach ($rows as $key => $array) {
	      		unset($array['salesman_id']);
	      		$array['status'] = ($array['status'] == 'Active') ? 1 : 2;
				$uniqueColumns = ['email' => $array['email']];
				$salesman->getResource()->insertUpdateOnDuplicate($array, $uniqueColumns);
			}

			if ($attributes) {
				foreach ($attributes as $salesmanId => $attributeArray) {
					if ($salesman->load($salesmanId)) {
						foreach ($attributeArray as $key => $value) {
							$attribute = Ccc::getModel('Eav_Attribute')->fetchRow("SELECT * FROM `eav_attribute` WHERE `entity_type_id` = 5 AND `code` = '{$key}'");
							$model = Ccc::getModel('Core_Table');
							$model->getResource()->setTableName("salesman_{$attribute->backend_type}")->setPrimaryKey('value_id');
							$arrayData = ['entity_id' => $salesmanId,'attribute_id' => $attribute->attribute_id,'value' => $value];
							$uniqueColumns = ['value' => $value];
							if (!$result = $model->getResource()->insertUpdateOnDuplicate($arrayData, $uniqueColumns)) {
								throw new Exception("Unable to save salesman_{$attribute->backend_type}", 1);
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
			$salesman = Ccc::getModel('Salesman');		
			if (!$salesman) {
				throw new Exception("Invalid request.", 1);
			}

			$salesmanAddress = Ccc::getModel("Salesman");
			$salesmanAddress->getResource()->setTableName('salesman_address');
			if (!$salesmanAddress) {
				throw new Exception("Invalid request.", 1);
			}

			$addHtml = $this->getLayout()->createBlock('Salesman_Edit')->setData(['salesman' => $salesman, 'salesmanAddress' => $salesmanAddress])->toHtml();
			$this->getResponse()->jsonResponse(['html' => $addHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}

	public function editAction()
	{
		try {
			if (!$id = (int)$this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			$salesman = Ccc::getModel('Salesman');
			if (!$salesman->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$salesmanAddress = Ccc::getModel('Salesman');
			$salesmanAddress->getResource()->setTableName('salesman_address');
			$salesmanAddress->load($id);

			$editHtml = $this->getLayout()->createBlock('Salesman_Edit')->setData(['salesman' => $salesman, 'salesmanAddress' => $salesmanAddress])->toHtml();
			$this->getResponse()->jsonResponse(['html' => $editHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}

	public function gridAction()
	{
		try {
			$currentPage = $this->getRequest()->getPost('p',1);
            $recordPerPage = $this->getRequest()->getPost('rpp',10);
            $layout = $this->getLayout();
            $gridHtml = $layout->createBlock('Salesman_Grid');
            $gridHtml->setCurrentPage($currentPage)->setRecordPerPage($recordPerPage);
            $gridHtml = $gridHtml->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
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

			if (!$postData1 = $this->getRequest()->getPost('salesman')) {
				throw new Exception("No data posted.", 1);
			}

			if (!$postData2 = $this->getRequest()->getPost('salesmanAddress')) {
				throw new Exception("No data posted.", 1);
			}

			$salesman = Ccc::getModel('Salesman');
			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$salesman->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}

				$salesman->updated_at = date('Y-m-d H:i:s');
			}
			else{
				$salesman->created_at = date('Y-m-d H:i:s');
			}
			unset($salesman->salesman_address_id);
			$salesman->setData($postData1);
			if (!$salesman->save()) {
				throw new Exception("Unable to save salesman", 1);
			}

			$salesmanAddress = Ccc::getModel('Salesman');
			$salesmanAddress->getResource()->setTableName('salesman_address');
			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$salesmanAddress->load($id)) {
					$salesmanAddress->salesman_id = $salesman->salesman_id;
					$salesmanAddress->getResource()->setPrimaryKey('address_id');
					$salesmanAddress->setData($postData2);
					$salesmanAddress->save();
				}
				$salesmanAddress->salesman_id = $salesman->salesman_id;
			}
			else{
				$salesmanAddress->salesman_id = $salesman->salesman_id;
				$salesmanAddress->getResource()->setPrimaryKey('address_id');
			}

			$salesmanAddress->setData($postData2);
			if (!$salesmanAddress->save()) {
				throw new Exception("Unable to save salesmanAddress", 1);
			}

			if ($attributes = $this->getRequest()->getPost('attribute')) {
				foreach($attributes as $backendType => $value){
					foreach ($value as $attributeId => $v) {
						if (is_array($v)) {
							$v = implode(",", $v);
						}

						$model = Ccc::getModel('Core_Table');
						$model->getResource()->setTableName("salesman_{$backendType}")->setPrimaryKey('value_id');
						$arrayData = ['entity_id' => $salesman->getId(),'attribute_id' => $attributeId,'value' => $v];
						$uniqueColumns = ['value' => $v];
						if (!$result = $model->getResource()->insertUpdateOnDuplicate($arrayData, $uniqueColumns)) {
							throw new Exception("Unable to save product_{backendType}", 1);
						}
					}
				}
			}

			$salesman->salesman_address_id = $salesmanAddress->address_id;
			unset($salesman->updated_at);
			if (!$salesman->save()) {
				throw new Exception("Unable to salesman address id.", 1);
			}
			$this->getMessage()->addMessage('Salesman saved successfully.');

			$gridHtml = $this->getLayout()->createBlock('Salesman_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}


	public function deleteAction()
	{
		try {
			if (!$id = (int)$this->getRequest()->getParam('id')) {
				throw new Exception("Invalid request.", 1);
			}

			if (!$salesman = Ccc::getModel('Salesman')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			if (!$salesman->delete()){
				throw new Exception("Unable to delete salesman.", 1);
			}
			$this->getMessage()->addMessage("Salesman deleted successfully.");

			$gridHtml = $this->getLayout()->createBlock('Salesman_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}
	
}

?>