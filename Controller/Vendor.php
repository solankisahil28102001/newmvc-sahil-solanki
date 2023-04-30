<?php

class Controller_Vendor extends Controller_Core_Action
{
	public function indexAction()
    {
        try { 
            $this->_setTitle('Manage Vendors');
            $indexBlock = $this->getLayout()->createBlock('Core_Template')->setTemplate('vendor/index.phtml');
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

      	$vendor = Ccc::getModel('Vendor');
      	$query = "SELECT * from `vendor` ORDER BY `vendor_id` DESC";  
      	
      	$result = $vendor->getResource()->fetchAll($query);
      	$header = [];
      	if ($result) {
            foreach($result as &$row)
            {  
	      		unset($row['created_at']);
				unset($row['updated_at']);
				unset($row['vendor_address_id']);
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
        $importBlock = $layout->createBlock('Core_Template')->setTemplate('vendor/import.phtml');
        $layout->getChild('content')->addChild('import', $importBlock);
        $this->renderLayout();
    }

    public function saveImportAction()
    {
        try {
            $upload = Ccc::getModel('Core_File_Upload')->setPath($_FILES['file']['full_path'])->setFile('file');
            $rows = Ccc::getModel('Core_File_Csv')->setFileName($upload->getFileName())->setPath($upload->getFileName())->read()->getRows();

            $vendor = Ccc::getModel('Vendor');
            foreach ($rows as $key => $row) {
                unset($row['vendor_id']);
                $uniqueColumns = ['email' => $row['email']];
                $vendor->getResource()->insertUpdateOnDuplicate($row, $uniqueColumns);
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
			$vendor = Ccc::getModel('Vendor');		
			if (!$vendor) {
				throw new Exception("Invalid request.", 1);
			}

			$vendorAddress = Ccc::getModel("Vendor");
			$vendorAddress->getResource()->setTableName('vendor_address');
			if (!$vendorAddress) {
				throw new Exception("Invalid request.", 1);
			}

			$addHtml = $this->getLayout()->createBlock('Vendor_Edit')->setData(['vendor' => $vendor, 'vendorAddress' => $vendorAddress])->toHtml();
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

			$vendor = Ccc::getModel('Vendor');
			if (!$vendor->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$vendorAddress = Ccc::getModel('Vendor');
			$vendorAddress->getResource()->setTableName('vendor_address');
			$vendorAddress->load($id);
			
			$editHtml = $this->getLayout()->createBlock('Vendor_Edit')->setData(['vendor' => $vendor, 'vendorAddress' => $vendorAddress])->toHtml();
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
			$gridHtml = $layout->createBlock('Vendor_Grid');
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

			if (!$postData1 = $this->getRequest()->getPost('vendor')) {
				throw new Exception("No data posted.", 1);
			}

			if (!$postData2 = $this->getRequest()->getPost('vendorAddress')) {
				throw new Exception("No data posted.", 1);
			}

			$vendor = Ccc::getModel('Vendor');
			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$vendor->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}
				unset($vendor->vendor_address_id);
				$vendor->updated_at = date('Y-m-d H:i:s');
			}
			else{
				$vendor->created_at = date('Y-m-d H:i:s');
			}

			$vendor->setData($postData1);
			if (!$vendor->save()) {
				throw new Exception("Unable to save vendor", 1);
			}
			$vendorAddress = Ccc::getModel('Vendor');
			$vendorAddress->getResource()->setTableName('vendor_address');
			if ($id = (int)$this->getRequest()->getParam('id')) {
				if (!$vendorAddress->load($id)) {
					$vendorAddress->vendor_id = $vendor->vendor_id;
					$vendorAddress->getResource()->setPrimaryKey('address_id');
					$vendorAddress->setData($postData2);
					$vendorAddress->save();
				}
				$vendorAddress->vendor_id = $vendor->vendor_id;
			}
			else{
				$vendorAddress->vendor_id = $vendor->vendor_id;
				$vendorAddress->getResource()->setPrimaryKey('address_id');
			}
			$vendorAddress->setData($postData2);
			if (!$vendorAddress->save()) {
				throw new Exception("Unable to save vendorAddress", 1);
			}
			$vendor->vendor_address_id = $vendorAddress->address_id;
			unset($vendor->updated_at);
			if (!$vendor->save()) {
				throw new Exception("Unable to vendor address id.", 1);
			}

			$this->getMessage()->addMessage('Vendor saved successfully.');

			$gridHtml = $this->getLayout()->createBlock('Vendor_Grid')->toHtml();
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

			if (!$vendor = Ccc::getModel('Vendor')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			if (!$vendor->delete()){
				throw new Exception("Unable to delete vendor.", 1);
			}
			$this->getMessage()->addMessage("Vendor deleted successfully.");

			$gridHtml = $this->getLayout()->createBlock('Vendor_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}
}