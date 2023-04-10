<?php

class Controller_Core_Action{

	protected $message = null;
	protected $adapter = null;
	protected $request = null;
	protected $url = null;
	protected $view = null;
	protected $layout = null;

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

	public function render()
	{
		$this->getView()->render();
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

}

