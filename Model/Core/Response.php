<?php

class Model_Core_Response
{
	protected $controller = null;

	protected $_jsonData = [
		'status' => 'success',
		'message' => 'success',
		'messageBlockHtml' => null
	];

	private function getController()
	{
		return $this->controller;
	}

	public function setController($controller)
	{
		$this->controller = $controller;
		return $this;
	}

	public function setJsonData($data)
	{
		$this->_jsonData = array_merge($this->_jsonData,$data);
		return $this;
	}

	public function getJsonData()
	{
		return $this->_jsonData;
	}

	public function setBody($content)
	{
		echo $content;
		header('Content-type: text/html');
	}

	public function jsonResponse($data)
	{	
		$this->setJsonData($data);
		$this->setMessageResponse();
		echo json_encode($this->getJsonData());
		@header('Content-type: application/json');
	}

	protected function setMessageResponse()
	{
		$messageHtml = $this->getController()->getLayout()->createBlock('Html_Message')->toHtml();
		$this->setJsonData(['messageBlockHtml' => $messageHtml]);
	}

}