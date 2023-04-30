<?php 

class Controller_Item extends Controller_Core_Action
{
	public function indexAction()
    {
        try { 
            $this->_setTitle('Manage Items');
            $indexBlock = $this->getLayout()->createBlock('Core_Template')->setTemplate('item/index.phtml');
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

      	$item = Ccc::getModel('Item');
      	if ($query = $this->buildEavAttributeQuery($item)) {
      		$query = $query;
      	}
      	else{
      		$query = "SELECT * from `item` ORDER BY `item_id` DESC";  
      	}
      	
      	$result = $item->getResource()->fetchAll($query);
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
        $importBlock = $layout->createBlock('Core_Template')->setTemplate('item/import.phtml');
        $layout->getChild('content')->addChild('import', $importBlock);
        $this->renderLayout();
    }

    public function saveImportAction()
	{
		try {
			$upload = Ccc::getModel('Core_File_Upload')->setPath($_FILES['file']['full_path'])->setFile('file');
			$rows = Ccc::getModel('Core_File_Csv')->setFileName($upload->getFileName())->setPath($upload->getFileName())->read()->getRows();

			$item = Ccc::getModel('Item');
			$attributes = [];
			foreach ($rows as $key => &$row) {
				foreach (array_keys($row) as $value) {
					$query = "SHOW COLUMNS FROM `item` LIKE '".$value."'";
					$result = Ccc::getModel('Item')->getResource()->getAdapter()->query($query);
					if ($result->num_rows == 0) {
						$attributes[$row['item_id']][$value] = $row[$value]; 
						unset($row[$value]);  
					}
				}
			}

			foreach ($rows as $key => $array) {
	      		unset($array['item_id']);
	      		$array['status'] = ($array['status'] == 'Active') ? 1 : 2;
				$uniqueColumns = ['sku' => $array['sku']];
				$item->getResource()->insertUpdateOnDuplicate($array, $uniqueColumns);
			}

			if ($attributes) {
				foreach ($attributes as $itemId => $attributeArray) {
					if ($item->load($itemId)) {
						foreach ($attributeArray as $key => $value) {
							$attribute = Ccc::getModel('Eav_Attribute')->fetchRow("SELECT * FROM `eav_attribute` WHERE `entity_type_id` = 7 AND `code` = '{$key}'");
							$model = Ccc::getModel('Core_Table');
							$model->getResource()->setTableName("item_{$attribute->backend_type}")->setPrimaryKey('value_id');
							$arrayData = ['entity_id' => $itemId,'attribute_id' => $attribute->attribute_id,'value' => $value];
							$uniqueColumns = ['value' => $value];
							if (!$result = $model->getResource()->insertUpdateOnDuplicate($arrayData, $uniqueColumns)) {
								throw new Exception("Unable to save item_{$attribute->backend_type}", 1);
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


	public function gridAction()
	{
		$currentPage = $this->getRequest()->getPost('p',1);
        $recordPerPage = $this->getRequest()->getPost('rpp',10);
        $layout = $this->getLayout();
        $gridHtml = $layout->createBlock('Item_Grid');
        $gridHtml->setCurrentPage($currentPage)->setRecordPerPage($recordPerPage);
        $gridHtml = $gridHtml->toHtml();
		$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
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
			$this->getResponse()->jsonResponse(['html' => $editHtml, 'element' => 'content-grid']);
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
			$this->getResponse()->jsonResponse(['html' => $addHtml, 'element' => 'content-grid']);
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
			$this->getMessage()->addMessage('Item deleted successfully.');

			$gridHtml = $this->getLayout()->createBlock('Item_Grid')->toHtml();
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
			
			$gridHtml = $this->getLayout()->createBlock('Item_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}
}