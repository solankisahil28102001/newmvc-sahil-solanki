<?php

class Controller_Core_Action{

	protected $message = null;
	protected $session = null;
	protected $adapter = null;
	protected $request = null;
	protected $response = null;
	protected $url = null;
	protected $view = null;
	protected $layout = null;


	protected function setResponse(Model_Core_Response $response)
	{
		$this->response = $response;
		return $this;
	}

	public function getResponse()
	{
		if ($this->response) {
			return $this->response;
		}
		$response = new Model_Core_Response();
		$response->setController($this);
		$this->setResponse($response);
		return $response;
	}

	protected function _setTitle($title)
	{
		$this->getLayout()->getChild('head')->setTitle($title);
		return $this;
	}

	public function setLayout(Block_Core_Layout $layout)
	{
		$this->layout = $layout;
		return $this;
	}

	public function getLayout()
	{
		if ($this->layout) {
			return $this->layout;
		}
		$layout = new Block_Core_Layout();
		$this->setLayout($layout);
		return $layout;
	}

	public function setView(Model_Core_View $view)
	{
		$this->view = $view;
		return $this;
	}

	public function getView()
	{
		if ($this->view) {
			return $this->view;
		}
		
		$view = Ccc::getModel('Core_View');
		$this->setView($view);
		return $view;
	}

	public function renderLayout()
	{	
		$this->getResponse()->setBody($this->getLayout()->toHtml());
	}
	
	public function setMessage(Model_Core_Message $message)
	{
		$this->message = $message;
		return $this;
	}

	public function getMessage()
	{
		if ($this->message) {
			return $this->message;
		}
		$message = new Model_Core_Message();
		$this->setMessage($message);
		return $message;
	}

	public function setSession(Model_Core_Session $session)
	{
		$this->session = $session;
		return $this;
	}

	public function getSession()
	{
		if ($this->session) {
			return $this->session;
		}
		$session = new Model_Core_Session();
		$this->setSession($session);
		return $session;
	}
	
	public function setUrlObj(Model_Core_Url $url)
	{
		$this->url = $url;
		return $this;
	}

	public function getUrlObj()
	{
		if ($this->url) {
			return $this->url;
		}
		$url = new Model_Core_Url();
		$this->setUrlObj($url);
		return $url;
	}

	protected function setRequest(Model_Core_Request $request)
	{
		$this->request = $request;
		return $this;
	}

	public function getRequest()
	{
		if ($this->request) {
			return $this->request;
		}
		$request = new Model_Core_Request();
		$this->setRequest($request);
		return $request;
	}


	protected function setAdapter(Model_Core_Adapter $adapter)
	{
		$this->adapter = $adapter;
		return $this;
	}

	public function getAdapter()
	{
		if($this->adapter){
			return $this->adapter;
		}
		$adapter = new Model_Core_Adapter();
		$this->setAdapter($adapter);
		return $adapter;
	}

	public function redirect($action=null,$controller=null,$params=[],$reset=false)
	{
		$url = $this->getUrlObj()->getUrl($action,$controller,$params,$reset);
		header("location: $url");
		exit();
	}

	public function buildEavAttributeQuery($obj)
	{
		if ($attributes = $obj->getAttributes()) {
			$tableName = $obj->getTableName();
			$primaryKey = $obj->getPrimaryKey();
			$query = "SELECT ";
			$columNames = "`$tableName`.*, ";
			$from = " FROM `$tableName`";
			$leftJoin = '';
		  	foreach ($attributes->getData() as $attribute) {
		  		$columNames .= "$attribute->code.`value` as $attribute->code, ";
		  		$leftJoin .= " LEFT JOIN `{$tableName}_{$attribute->backend_type}` as $attribute->code ON `$tableName`.`$primaryKey` = $attribute->code.`entity_id` AND $attribute->code.`attribute_id` = {$attribute->attribute_id}";
		  	}
		  	$columNames = rtrim($columNames," ,");
		  	return $query.$columNames.$from.$leftJoin." ORDER BY `$primaryKey` DESC";
		}
		return null;
	}

}

