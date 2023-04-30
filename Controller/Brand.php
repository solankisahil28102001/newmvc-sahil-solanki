<?php

class Controller_Brand extends Controller_Core_Action
{
	public function indexAction()
    {
        try { 
            $layout = $this->getLayout();
            $this->_setTitle('Manage Brands');
            $indexBlock = $layout->createBlock('Core_Template')->setTemplate('brand/index.phtml');
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

      	$brand = Ccc::getModel('Brand');
      	$query = "SELECT * from `brand` ORDER BY `brand_id` DESC";  
      	
      	$result = $brand->getResource()->fetchAll($query);
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
        $importBlock = $layout->createBlock('Core_Template')->setTemplate('brand/import.phtml');
        $layout->getChild('content')->addChild('import', $importBlock);
        $this->renderLayout();
    }

    public function saveImportAction()
    {
        try {
            $upload = Ccc::getModel('Core_File_Upload')->setPath($_FILES['file']['full_path'])->setFile('file');
            $rows = Ccc::getModel('Core_File_Csv')->setFileName($upload->getFileName())->setPath($upload->getFileName())->read()->getRows();

            $brand = Ccc::getModel('Brand');
            foreach ($rows as $key => $row) {
                unset($row['brand_id']);
                $uniqueColumns = ['name' => $row['name']];
                $brand->getResource()->insertUpdateOnDuplicate($row, $uniqueColumns);
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
			if (!$brand = Ccc::getModel('Brand')) {
				throw new Exception("Invalid request.", 1);
			}

			$addHtml = $this->getLayout()->createBlock('Brand_Edit');
			$addHtml->setRow($brand);
			$addHtml = $addHtml->toHtml();
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

			if (!$brand = Ccc::getModel('Brand')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}
			
			$editHtml = $this->getLayout()->createBlock('Brand_Edit');
			$editHtml->setRow($brand);
			$editHtml = $editHtml->toHtml();
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
            $gridHtml = $layout->createBlock('Brand_Grid');
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

			if (!$postData = $this->getRequest()->getPost('brand')) {
				throw new Exception("No data posted.", 1);
			}

			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$brand = Ccc::getModel('Brand')->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}
				$brand->updated_at = date('Y-m-d H:i:s');
			}
			else{
				$brand = Ccc::getModel('Brand');
				$brand->created_at = date('Y-m-d H:i:s');
			}

			$brand->setData($postData);

			if (!$brand->save()) {
				throw new Exception("Unable to save brand", 1);
			}
			$this->getMessage()->addMessage('Brand saved successfully.');

			$gridHtml = $this->getLayout()->createBlock('Brand_Grid')->toHtml();
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

			if (!$brand = Ccc::getModel('Brand')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			if(!$brand->delete()){
				throw new Exception("Unable to delete Brand", 1);
			}
			$this->getMessage()->addMessage("Brand deleted successfully.");

			$gridHtml = $this->getLayout()->createBlock('Brand_Grid')->toHtml();
            $this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}
}