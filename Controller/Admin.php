<?php

class Controller_Admin extends Controller_Core_Action
{

    public function indexAction()
    {
        try { 
            $this->_setTitle('Manage Categories');
            $indexBlock = $this->getLayout()->createBlock('Core_Template')->setTemplate('category/index.phtml');
            $this->getLayout()->getChild('content')->addChild('index', $indexBlock);
            $this->renderLayout();
        } catch (Exception $e) {
            
        }
    }

    public function gridAction()
    {
        try {
            $currentPage = $this->getRequest()->getPost('p',1);
            $recordPerPage = $this->getRequest()->getPost('rpp',10);
            $layout = $this->getLayout();
            $gridHtml = $layout->createBlock('Admin_Grid');
            $gridHtml->setCurrentPage($currentPage)->setRecordPerPage($recordPerPage);
            $gridHtml = $gridHtml->toHtml();
            $this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
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
            $this->getResponse()->jsonResponse(['html' => $addHtml, 'element' => 'content-grid']);
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
            $this->getResponse()->jsonResponse(['html' => $editHtml, 'element' => 'content-grid']);
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
            $this->getMessage()->addMessage("Admin saved successfully.");
            $gridHtml = $this->getLayout()->createBlock('Admin_Grid')->toHtml();
            $this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
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
            
            $this->getMessage()->addMessage("Admin deleted successfully.");
            $gridHtml = $this->getLayout()->createBlock('Admin_Grid')->toHtml();
            $this->getResponse()->jsonResponse(['html' => $gridHtml, 'element' => 'content-grid']);
        } catch (Exception $e) {
            $this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
            $this->redirect('index');
        }
    }
}