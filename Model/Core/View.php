<?php

class Model_Core_View
{
	protected $template = '';
	protected $data = [];

	public function __construct()
	{
		
	}

	public function setTemplate($template)
	{
		$this->template = $template;
		return $this;
	}

	public function getTemplate()
	{
		return $this->template;
	}


	public function __get($key)
	{
		if (array_key_exists($key, $this->data)) {
			return $this->data[$key];
		}
		return null;
	}

	public function __unset($key)
	{
		if (array_key_exists($key, $this->data)) {
			unset($this->data[$key]);
		}
	}

	public function __set($key, $value)
	{
		$this->data[$key] = $value;
	}

	public function addData($key, $value)
	{
		$this->data[$key] = $value;
		return $this;
	}

	public function getData($key=null)
	{
		if ($key == null) {
			return $this->data;
		}
		if (array_key_exists($key, $this->data)) {
			return $this->data[$key];
		}
		return null;
	}

	public function getUrl($action = null, $controller = null, $params = [], $resetParams = false)
	{
		return Ccc::getModel('Core_Url')->getUrl($action, $controller, $params, $resetParams);
	}

	public function getMessage()
	{
		return Ccc::getModel('Core_Message');
	}

	public function getRequest()
	{
		return Ccc::getModel('Core_Request');
	}

	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	public function render()
	{
		require "View".DS.$this->getTemplate();
	}

}