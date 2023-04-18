<?php 
error_reporting(E_ALL);
define("DS", DIRECTORY_SEPARATOR);
date_default_timezone_set('Asia/Kolkata');

spl_autoload_register(function ($className) {
	$classPath = str_replace("_", "/", $className);
    require $classPath . '.php';
});

class Ccc{
	public static function init()
	{
		$front = new Controller_Core_Front();
		$front->init();	
	}

	public static function getSingleton($className)
	{	
		$className = "Model_".$className;
		if (array_key_exists($className, $GLOBALS)) {
			return $GLOBALS[$className];
		}
		$GLOBALS[$className] = new $className();
		return $GLOBALS[$className];
	}

	public static function getModel($className)
	{
		$className = 'Model_'.$className;
		return new $className();
	}

	public static function register($key, $value)
	{
		$GLOBALS[$key] = $value;
	}

	public static function getRegistry($key)
	{
		if (array_key_exists($key, $GLOBALS)) {
			return $GLOBALS[$key];
		}
		return null;
	}

	public static function log($data, $filename = 'system.log', $newFile = false)
	{
		self::getSingleton('Core_Log')->log($data, $filename, $newFile);
	}

	public static function getBaseDir($subDir = null)
	{
		$dir = getcwd();
		if ($subDir) {
			$dir = $dir.$subDir;
		}
		return $dir;
	}

}

Ccc::init();

?>