<?php 

class Controller_Core_Front{

	public function init()
	{
		try {
			$message = Ccc::getModel('Core_Message');
			$message->getSession()->start();

			$request = Ccc::getModel('Core_Request');
			$actionName = $request->getActionName()."Action";
			$controllerName = $request->getControllerName();

			$className = "Controller_".ucwords($controllerName,"_");
			$filePath = str_replace("_", "/", $className);

			if (!file_exists("$filePath.php")) {
				throw new Exception("$className does not exist", 1);
			}
			else{
				require_once "{$filePath}.php";
			}

			$controller = new $className();
			if (!method_exists($controller, $actionName)) {
				throw new Exception("Method does not exist.", 1);
			}

			$controller->$actionName();
		} catch (Exception $e) {
			$action = new Controller_Core_Action();
			$message->addMessage($e->getMessage(), Model_Core_Message::FAILURE);
			$action->redirect('grid',$controllerName,[], true);
		}
	}	
}

