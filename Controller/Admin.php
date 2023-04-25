<?php

class Controller_Admin extends Controller_Core_Action
{

    public function indexAction()
    {
        try { 
            $this->_setTitle('Manage Categories');
            $indexBlock = $this->getLayout()->createBlock('Core_Template')->setTemplate('category/index.phtml');
            $this->getLayout()->getChild('content')->addChild('index', $indexBlock);
            echo $this->getLayout()->toHtml();
        } catch (Exception $e) {
            
        }
    }

    public function gridAction()
    {
        try {
            $gridHtml = $this->getLayout()->createBlock('Admin_Grid')->toHtml();
            echo json_encode(['html' => $gridHtml, 'element' => 'content-grid']);
            @header('Content-type: application/json');
        } catch (Exception $e) {
            $this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
        }
    }	

    public function addAction()
    {
        try {
            if (!($admin = Ccc::getModel('Admin'))) {
                throw new Exception("Invalid request.", 1);
            };
            
            $addHtml = $this->getLayout()->createBlock('Admin_Edit')->setData(['admin' => $admin])->toHtml();
            echo json_encode(['html' => $addHtml, 'element' => 'content-grid']);
            @header('Content-type: application/json');
        } catch (Exception $e) {
            $this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
            $this->redirect('index');
        }    
    }


    public function editAction()
    {
        try {
            if (!($id = $this->getRequest()->getParam('id'))) {
                throw new Exception("Invalid request.", 1);
            }
            if (!($admin = Ccc::getModel('Admin')->load($id))) {
                throw new Exception("Invalid Id.", 1);
            }

            $editHtml = $this->getLayout()->createBlock('Admin_Edit')->setData(['admin' => $admin])->toHtml();
            echo json_encode(['html' => $editHtml, 'element' => 'content-grid']);
            @header('Content-type: application/json');
        } catch (Exception $e) {
            $this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
            $this->redirect('index');
        }
    }


    public function saveAction()
    {   
        try {
            if (!$this->getRequest()->isPost()) {
                throw new Exception("Invalid request.", 1);
            }

            if (!$postData = $this->getRequest()->getPost('admin')) {
                throw new Exception("No data posted.", 1);
            }

            if (($id = $this->getRequest()->getParam('id'))) {
               if (!($admin = Ccc::getModel('Admin')->load($id))) {
                    throw new Exception("Invalid Id.", 1);
                }
                $admin->updated_at = date('Y-m-d H:i:s a');
            }
            else{
                $admin = Ccc::getModel('Admin');
                $admin->created_at = date('Y-m-d H:i:s a');
            }

            $admin->setData($postData);

            if (!$admin->save()) {
                throw new Exception("Unable to save admin.", 1);
            }

            $gridHtml = $this->getLayout()->createBlock('Admin_Grid')->toHtml();
            @header('Content-type: application/json');
            echo json_encode(['html' => $gridHtml, 'element' => 'content-grid', 'message' => "Admin saved successfully."]);
        } catch (Exception $e) {
            $this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
            $this->redirect('index');
        }
    }


    public function deleteAction()
    {
        try {

            if (!($id = $this->getRequest()->getParam('id'))) {
                throw new Exception("Invalid request.", 1);
            }
            if (!($admin = Ccc::getModel('Admin')->load($id))) {
                throw new Exception("Invalid Id.", 1);
            }

            if (!$admin->delete()) {
                throw new Exception("Unable to delete admin.", 1);
            }
            
            $gridHtml = $this->getLayout()->createBlock('Admin_Grid')->toHtml();
            @header('Content-type: application/json');
            echo json_encode(['html' => $gridHtml, 'element' => 'content-grid','message' => "Admin deleted successfully."]);

        } catch (Exception $e) {
            $this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
            $this->redirect('index');
        }
    }
}