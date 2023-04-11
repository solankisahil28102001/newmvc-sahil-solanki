<?php 

class Model_Core_Url
{
	public function getCurrentUrl()
	{
		return $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}

	public function getUrl($action=null, $controller=null, $params=[], $reset = false)
	{
		$request = new Model_Core_Request();
		$url = $this->getCurrentUrl();
		$url = str_replace($_SERVER['QUERY_STRING'], '', $url);

		$final = $request->getParam();

		if ($reset) {
			$final = [];
		}

		($action) ? $final['a'] = $action : $final['a'] = $request->getActionName();
		($controller) ? $final['c'] = $controller : $final['c'] = $request->getControllerName();
		
		if ($params) {
			$final = array_merge($final,$params);
		}

		$url = $url.http_build_query($final);
		return $url;

	}
}