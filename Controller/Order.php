<?php

class Controller_Order extends Controller_Core_Action
{

	public function indexAction()
	{
		try{	
			$this->_setTitle('Manage Orders');
			$indexBlock = $this->getLayout()->createBlock('Core_Template')->setTemplate('order/index.phtml');
			$this->getLayout()->getChild('content')->addChild('indexBlock', $indexBlock);
			$this->renderLayout();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
		}
	}

	public function exportAction()
	{
		@header('Content-Type: text/csv; charset=utf-8');  
      	@header('Content-Disposition: attachment; filename=data.csv');  
      	$output = fopen("php://output", "w");  

      	$order = Ccc::getModel('Order');
      	$query = "SELECT * from `orders` ORDER BY `order_id` DESC";  
      	
      	$result = $order->getResource()->fetchAll($query);
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

	public function gridAction()
	{
		try {
			$currentPage = $this->getRequest()->getPost('p',1);
			$recordPerPage = $this->getRequest()->getPost('rpp',10);
			$gridHtml = $this->getLayout()->createBlock('Order_Grid');
			$gridHtml->setCurrentPage($currentPage)->setRecordPerPage($recordPerPage);
			$gridHtml = $gridHtml->toHtml();
			$this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$this->redirect('index');
		}
	}

}