<?php

class engine_global_helper
{
	public static function isJSON($content)
	{
		json_decode($content);
		return json_last_error() === JSON_ERROR_NONE;
	}

	public static function isJSONFile($file)
	{
		if (!is_readable($file)) {
			return false;
		}

		if (!self::isJSON(file_get_contents($file))) {
			return false;
		}

		return true;
	}

	public static function get_post_var($var)
	{
		if (array_key_exists($var, $_POST) and isset($_POST[$var])) {
			return $_POST[$var];
		} else {
			return null;
		}
	}
}

?>