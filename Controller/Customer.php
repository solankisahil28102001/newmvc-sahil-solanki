<?php 

class Controller_Customer extends Controller_Core_Action{

	public function indexAction()
	{
		$this->_setTitle('Manage Customers');
		$indexBlock = $this->getLayout()->createBlock('Core_Template')->setTemplate('customer/index.phtml');
		$this->getLayout()->getChild('content')->addChild('index', $indexBlock);
		$this->renderLayout();
	}

	public function exportAction()
	{
		@header('Content-Type: text/csv; charset=utf-8');  
      	@header('Content-Disposition: attachment; filename=data.csv');  
      	$output = fopen("php://output", "w");  

      	$customer = Ccc::getModel('Customer');
      	if ($query = $this->buildEavAttributeQuery($customer)) {
      		$query = $query;
      	}
      	else{
      		$query = "SELECT * from `customer` ORDER BY `customer_id` DESC";  
      	}
      	
      	$result = $customer->getResource()->fetchAll($query);
      	$header = [];
      	if ($result) {
            foreach($result as &$row)
            {  
	      		unset($row['created_at']);
				unset($row['updated_at']);
				unset($row['shipping_address_id']);
	      		unset($row['billing_address_id']);
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
        $importBlock = $layout->createBlock('Core_Template')->setTemplate('customer/import.phtml');
        $layout->getChild('content')->addChild('import', $importBlock);
        $this->renderLayout();
    }

    public function saveImportAction()
	{
		try {
			$upload = Ccc::getModel('Core_File_Upload')->setPath($_FILES['file']['full_path'])->setFile('file');
			$rows = Ccc::getModel('Core_File_Csv')->setFileName($upload->getFileName())->setPath($upload->getFileName())->read()->getRows();

			$customer = Ccc::getModel('Customer');
			$attributes = [];
			foreach ($rows as $key => &$row) {
				foreach (array_keys($row) as $value) {
					$query = "SHOW COLUMNS FROM `customer` LIKE '".$value."'";
					$result = Ccc::getModel('Customer')->getResource()->getAdapter()->query($query);
					if ($result->num_rows == 0) {
						$attributes[$row['customer_id']][$value] = $row[$value]; 
						unset($row[$value]);  
					}
				}
			}

			foreach ($rows as $key => $array) {
	      		unset($array['customer_id']);
	      		$array['status'] = ($array['status'] == 'Active') ? 1 : 2;
				$uniqueColumns = ['email' => $array['email']];
				$customer->getResource()->insertUpdateOnDuplicate($array, $uniqueColumns);
			}

			if ($attributes) {
				foreach ($attributes as $customerId => $attributeArray) {
					if ($customer->load($customerId)) {
						foreach ($attributeArray as $key => $value) {
							$attribute = Ccc::getModel('Eav_Attribute')->fetchRow("SELECT * FROM `eav_attribute` WHERE `entity_type_id` = 3 AND `code` = '{$key}'");
							$model = Ccc::getModel('Core_Table');
							$model->getResource()->setTableName("customer_{$attribute->backend_type}")->setPrimaryKey('value_id');
							$arrayData = ['entity_id' => $customerId,'attribute_id' => $attribute->attribute_id,'value' => $value];
							$uniqueColumns = ['value' => $value];
							if (!$result = $model->getResource()->insertUpdateOnDuplicate($arrayData, $uniqueColumns)) {
								throw new Exception("Unable to save customer_{$attribute->backend_type}", 1);
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
			if (!$currentPage = $this->getRequest()->getParam('p')) {
				$currentPage = 1;
			}
			if (!$customer = Ccc::getModel('Customer')) {
				throw new Exception("Invalid request.", 1);
			}

			$addHtml = $this->getLayout()->createBlock('Customer_Edit')->setData(['customer' => $customer, 'shippingAddress' => $customer, 'billingAddress' => $customer])->toHtml();
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

			if (!$customer = Ccc::getModel('Customer')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$editHtml = $this->getLayout()->createBlock('Customer_Edit')->setData(['customer' => $customer, 'shippingAddress' => $customer, 'billingAddress' => $customer])->toHtml();
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
            $gridHtml = $layout->createBlock('Customer_Grid');
            $gridHtml->setCurrentPage($currentPage)->setRecordPerPage($recordPerPage);
            $gridHtml = $gridHtml->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
	}
	
	protected function _saveCustomer()
	{
		if (!$postData = $this->getRequest()->getPost('customer')) {
			throw new Exception("No data posted.", 1);
		}

		if ($id = (int)$this->getRequest()->getParam('id')) {
			if (!$customer = Ccc::getModel('Customer')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$customer->updated_at = date('Y-m-d H:i:s');
		}
		else{
			$customer = Ccc::getModel('Customer');
			$customer->created_at = date('Y-m-d H:i:s');
		}
		unset($customer->shipping_address_id);
		unset($customer->billing_address_id);
		$customer->setData($postData);
		if (!$customer->save()) {
			throw new Exception("Unable to save customer", 1);
		}
		return $customer;
	}

	public function _saveBillingAddress($customer)
	{
		if (!$postData = $this->getRequest()->getPost('billingAddress')) {
			throw new Exception("No data posted.", 1);
		}

		$billingAddress = $customer->getBillingAddress();
		if (!$billingAddress) {
			$billingAddress = Ccc::getModel('Customer_Address');
			$billingAddress->updated_at = date('Y-m-d H:i:s');
		}
		else{
			$billingAddress->created_at = date('Y-m-d H:i:s');
		}
		$billingAddress->setData($postData);
		$billingAddress->customer_id = $customer->getId();
		if (!$billingAddressId = $billingAddress->save()) {
			throw new Exception("Unable to save billingAddress", 1);
		}

		return $billingAddress;
	}

	public function _saveShippingAddress($customer)
	{
		if (!$this->getRequest()->getPost('checkbox')) {
			$postData = $this->getRequest()->getPost('shippingAddress');
		}
		else{
			$postData = $this->getRequest()->getPost('billingAddress');
		}
		if (!$postData) {
			throw new Exception("No data posted.", 1);
		}

		$shippingAddress = $customer->getShippingAddress();
		if (!$shippingAddress) {
			$shippingAddress = Ccc::getModel('Customer_Address');
			$shippingAddress->updated_at = date('Y-m-d H:i:s');
		}
		else{
			$shippingAddress->created_at = date('Y-m-d H:i:s');
		}
		$shippingAddress->setData($postData);
		$shippingAddress->customer_id = $customer->getId();
		if (!$shippingAddressId = $shippingAddress->save()) {
			throw new Exception("Unable to save shippingAddress", 1);
		}
		return $shippingAddress;
	}

	public function saveAction()
	{
		try {
			if (!$this->getRequest()->isPost()) {		
				throw new Exception("Invalid request.", 1);
			}

			$customer = $this->_saveCustomer();
			$billingAddress = $this->_saveBillingAddress($customer);
			$shippingAddress = $this->_saveShippingAddress($customer);

			$customer->billing_address_id = $billingAddress->address_id;
			$customer->shipping_address_id = $shippingAddress->address_id;
			unset($customer->updated_at);
			if (!$customer->save()) {
				throw new Exception("Unable to save customer", 1);
			}

			if ($attributes = $this->getRequest()->getPost('attribute')) {
				foreach($attributes as $backendType => $value){
					foreach ($value as $attributeId => $v) {
						if (is_array($v)) {
							$v = implode(",", $v);
						}

						$model = Ccc::getModel('Core_Table');
						$model->getResource()->setTableName("customer_{$backendType}")->setPrimaryKey('value_id');
						$arrayData = ['entity_id' => $customer->getId(),'attribute_id' => $attributeId,'value' => $v];
						$uniqueColumns = ['value' => $v];
						if (!$result = $model->getResource()->insertUpdateOnDuplicate($arrayData, $uniqueColumns)) {
							throw new Exception("Unable to save product_{backendType}", 1);
						}
					}
				}
			}

			$this->getMessage()->addMessage('Customer saved successfully.');

			$gridHtml = $this->getLayout()->createBlock('Customer_Grid')->toHtml();
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

			if (!$customer = Ccc::getModel('Customer')->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			if (!$customer->delete()){
				throw new Exception("Unable to delete customer.", 1);
			}
			$this->getMessage()->addMessage("Customer deleted successfully.");

			$gridHtml = $this->getLayout()->createBlock('Customer_Grid')->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index', null, null, true);
		}
	}
}