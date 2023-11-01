<?php

/* ENGINE > GLOBAL > IMPORT */

class engine_global_import
{
	private static $components = [
		"frontend",
		"backend"
	];

	public static function run($component = null)
	{
		if (!in_array($component, self::$components) and !is_null($component)) {
			# Exit if component doesnt exist, but is set (not null).
			return false;
		} 
		
		# Import Basic Constants
		self::import_constants();

		# import global
		self::import_component("global");
		
		if (is_null($component)) {
			# Import all components if component is not set (null).
			foreach (self::$components as $this_component) {
				self::import_component($this_component);
			}
		} else {
			# Import specific component if component exists.
			self::import_component($component);
		}

		return true;
	}

	private static function import_constants(){
		define( "PROJECT_ROOT", realpath(__DIR__ . "/../../..") );
	}

	private static function import_component($component)
	{
		self::require_once_recursive(PROJECT_ROOT . "/engine/code/" . $component . "/");
		self::require_once_recursive(PROJECT_ROOT . "/application/code/" . $component . "/");
	}

	private static function require_once_recursive($directory)
	{
		if (is_dir($directory)) {
			$scan = scandir($directory);
			unset($scan[0], $scan[1]); //unset . and ..
			foreach ($scan as $file) {
				if (is_dir($directory . "/" . $file)) {
					self::require_once_recursive($directory . "/" . $file);
				} else {
					if (strpos($file, '.class.php') !== false) {
						require_once($directory . "/" . $file);
					}
				}
			}
		}
	}
}

?>