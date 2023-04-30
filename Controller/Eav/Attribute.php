<?php 

class Controller_Eav_Attribute extends Controller_Core_Action
{

	public function indexAction()
    {
        try { 
            $this->_setTitle('Manage Eav Attributes');
            $indexBlock = $this->getLayout()->createBlock('Core_Template')->setTemplate('eav/attribute/index.phtml');
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

      	$attribute = Ccc::getModel('Eav_Attribute');
      	$query = "SELECT * from `eav_attribute` ORDER BY `attribute_id` DESC";  
      	
      	$result = $attribute->getResource()->fetchAll($query);
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
        $importBlock = $layout->createBlock('Core_Template')->setTemplate('eav/attribute/import.phtml');
        $layout->getChild('content')->addChild('import', $importBlock);
        $this->renderLayout();
    }

    public function saveImportAction()
    {
        try {
            $upload = Ccc::getModel('Core_File_Upload')->setPath($_FILES['file']['full_path'])->setFile('file');
            $rows = Ccc::getModel('Core_File_Csv')->setFileName($upload->getFileName())->setPath($upload->getFileName())->read()->getRows();

            $attribute = Ccc::getModel('Eav_Attribute');
            foreach ($rows as $key => $row) {
                unset($row['attribute_id']);
                $uniqueColumns = ['entity_type_id' => $row['entity_type_id']];
                $attribute->getResource()->insertUpdateOnDuplicate($row, $uniqueColumns);
            }

            $this->getMessage()->addMessage("Data inserted successfully.");
        } catch (Exception $e) {
            $this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
        }
        $this->redirect('index');
    }

	public function gridAction()
	{
		try {
			$currentPage = $this->getRequest()->getPost('p',1);
            $recordPerPage = $this->getRequest()->getPost('rpp',10);
            $layout = $this->getLayout();
            $gridHtml = $layout->createBlock('Eav_Attribute_Grid');
            $gridHtml->setCurrentPage($currentPage)->setRecordPerPage($recordPerPage);
            $gridHtml = $gridHtml->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->redirect('index', null, null, true);
		}
	}

	public function addAction()
	{
		try {
			$attribute = Ccc::getModel('Eav_Attribute');
			$options = Ccc::getModel('Eav_Attribute')->getCollection();
			$addHtml = $this->getLayout()->createBlock('Eav_Attribute_Edit')->setData(['attribute' => $attribute, 'options' => $options])->toHtml();
			$this->getResponse()->jsonResponse(['html' => $addHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}

	public function editAction()
	{
		try {
			$layout = $this->getLayout();
			if (!($id = (int)$this->getRequest()->getParam('id'))) {
				throw new Exception("Invalid request.", 1);
			}

			if (!($attribute = Ccc::getModel('Eav_Attribute')->load($id))) {
				throw new Exception("Invalid Id.", 1);
			}
			
			$query = "SELECT * FROM `eav_attribute_option` WHERE  `attribute_id` = '{$id}' ORDER BY `position`";
			$options = Ccc::getModel('Eav_Attribute')->fetchAll($query);
			$editHtml = $this->getLayout()->createBlock('Eav_Attribute_Edit')->setData(['attribute' => $attribute, 'options' => $options])->toHtml();
			$this->getResponse()->jsonResponse(['html' => $editHtml, 'element' => 'content-grid']);
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

			if (!$postData = $this->getRequest()->getPost('attribute')) {
				throw new Exception("No data posted.", 1);
			}
			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$attribute = Ccc::getModel('Eav_Attribute')->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}
			}
			else{
				$attribute = Ccc::getModel('Eav_Attribute');
			}

			$attribute->setData($postData);
			if (!$attribute->save()) {
				throw new Exception("Unable to save attribute", 1);
			}
			
			if (!$options = $this->getRequest()->getPost('option')){
				$options = [];
				$options['exist'] = [];
			}
			else{
				if (!array_key_exists('exist', $options)) {
					$options['exist'] = [];
				}
			}	

			if (!$positions = $this->getRequest()->getPost('position')){
				$positions = [];
			}
			else{
				if (!array_key_exists('exist', $positions)) {
					$positions['exist'] = [];
				}
			}			

			$optionsObj = Ccc::getModel('Eav_Attribute_Option');
			$query = "SELECT * FROM `eav_attribute_option` WHERE  `attribute_id` = '{$id}'";
			if ($data = $optionsObj->fetchAll($query)){
				foreach ($data->getData() as $row) {
					if (!array_key_exists($row->option_id, $options['exist'])) {
						$row->setData(['option_id' => $row->option_id]);
						if (!$row->delete()) {
							throw new Exception("Unable to delete data.", 1);
						}
					}
				}
			}

			
			if ($options){
				if (array_key_exists('exist', $options)) {
					if ($exist = $options['exist']) {
						foreach ($exist as $optionId => $name) {
							$attributeOption = Ccc::getModel('Eav_Attribute_Option');
							$attributeOption->setData(['option_id' => $optionId, 'name' => $exist[$optionId]]);
							if (!$attributeOption->save()) {
								throw new Exception("Unable to update data.", 1);
							}
						}
					}
				}
				
				if (array_key_exists('new', $options)) {
					if ($newOption = $options['new']) {
						$newPosition = $positions['new'];
						foreach ($newOption as $optionId => $name) {
							$attributeOption = Ccc::getModel('Eav_Attribute_Option');
							$attributeOption->setData(['attribute_id' => $attribute->attribute_id,'name' => $newOption[$optionId], 'position' => $newPosition[$optionId]]);
							if (!$attributeOption->save()) {
								throw new Exception("Unable to insert data.", 1);
							}
						}
					}				
				}
			}

			if ($positions){
				if (array_key_exists('exist', $positions)) {
					if ($exist = $positions['exist']) {
						foreach ($exist as $optionId => $position) {
							$attributeOption = Ccc::getModel('Eav_Attribute_Option');
							$attributeOption->setData(['option_id' => $optionId, 'position' => $exist[$optionId]]);
							if (!$attributeOption->save()) {
								throw new Exception("Unable to update data.", 1);
							}
						}
					}
				}
			}
			$this->getMessage()->addMessage('Attribute saved successfully.');
			
			$gridHtml = $this->getLayout()->createBlock('Eav_Attribute_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}


	public function deleteAction()
	{
		try {
			if (!($id = (int)$this->getRequest()->getParam('id'))) {
				throw new Exception("Invalid request.", 1);
			}

			if (!($attribute = Ccc::getModel('Eav_Attribute')->load($id))) {
				throw new Exception("Invalid Id.", 1);
			}

			if(!$attribute->delete()){
				throw new Exception("Unable to delete attribute", 1);
			}
			$this->getMessage()->addMessage("Attribute deleted successfully.");

			$gridHtml = $this->getLayout()->createBlock('Eav_Attribute_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}
}