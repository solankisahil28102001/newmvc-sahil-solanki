<?php

class Model_Core_Request
{
	public function getPost($key=null,$value=null)
	{
		if ($key == null) {
			return $_POST;
		}
		if (array_key_exists($key, $_POST)) {
			return $_POST[$key];
		}
		return $value;
	}

	public function getParam($key=null,$value=null)
	{
		if ($key == null) {
			return $_GET;
		}
		if (array_key_exists($key, $_GET)) {
			return $_GET[$key];
		}
		return $value;
	}
	public function isPost()
	{
		if ($_SERVER['REQUEST_METHOD']!='POST') {
			return false;
		}
		return true;
	}
	public function getActionName()
	{
		return $this->getParam('a','grid');
	}

	public function getControllerName()
	{
		return $this->getParam('c','product');
	}
}