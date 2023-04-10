<?php

class Controller_Admin extends Controller_Core_Action
{
    public function gridAction()
    {
        try {
            $layout = $this->getLayout();
            $grid = new Block_Admin_Grid();
            $layout->getChild('content')->addChild('grid', $grid);
            $layout->render();
        } catch (Exception $e) {
            $this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
        }
    }	

    public function addAction()
    {
        try {
            $layout = $this->getLayout();
            $edit = new Block_Admin_Edit();
            if (!($admin = Ccc::getModel('Admin'))) {
                throw new Exception("Invalid request.", 1);
            };
            
            $edit->setData(['admin' => $admin]);
            $layout->getChild('content')->addChild('edit', $edit);
            $layout->render();
        } catch (Exception $e) {
            $this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
            $this->redirect('grid',null,[],true);
        }    
    }


    public function editAction()
    {
        try {
            $layout = $this->getLayout();
            $edit = new Block_Admin_Edit();
            if (!($id = $this->getRequest()->getParam('id'))) {
                throw new Exception("Invalid request.", 1);
            }
            if (!($admin = Ccc::getModel('Admin')->load($id))) {
                throw new Exception("Invalid Id.", 1);
            }

            $edit->setData(['admin' => $admin]);
            $layout->getChild('content')->addChild('edit', $edit);
            $layout->render();
        } catch (Exception $e) {
            $this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
            $this->redirect('grid',null,[],true);
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
        } catch (Exception $e) {
            $this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
        }
        $this->redirect('grid',null, [], true);
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
        } catch (Exception $e) {
            $this->getMessage()->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
        }
        $this->redirect('grid',null,[],true);
    }
}