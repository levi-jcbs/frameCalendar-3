<?php

/* ENGINE > GLOBAL > MANIFEST */

class engine_global_manifest
{
	public static $vars = [];

	public static function init()
	{
		if (!engine_global_helper::isJSONFile(PROJECT_ROOT . "/application/manifest.json")) {
			return false;
		}

		self::$vars = json_decode(file_get_contents(PROJECT_ROOT . "/application/manifest.json"), true);

		return true;
	}
}

?>