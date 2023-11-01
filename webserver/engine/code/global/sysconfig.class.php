<?php

/* ENGINE > GLOBAL > SYSCONFIG */

/* GLOBAL > SYSCONFIG */

class engine_global_sysconfig
{
	/* PUBLIC PROPERTIES */
	public static $vars = [];

	/* PUBLIC METHODS */
	public static function init()
	{
		$config_final_data = array();
		$config_order = engine_global_manifest::$vars["sysconfig"]["order"];
		$config_variables = engine_global_manifest::$vars["sysconfig"]["variables"];

		$config_file_data = array();
		$config_file_location = PROJECT_ROOT . engine_global_manifest::$vars["sysconfig"]["file"]["location"];
		$config_file_enabled = engine_global_manifest::$vars["sysconfig"]["file"]["enabled"];

		$config_env_data = array();
		$config_env_enabled = engine_global_manifest::$vars["sysconfig"]["env"]["enabled"];

		if (engine_global_helper::isJSONFile($config_file_location) and $config_file_enabled) {
			$config_file_data = array_change_key_case(json_decode(file_get_contents($config_file_location), true, 2), CASE_UPPER);
		}

		if ($config_env_enabled) {
			$config_env_data = array_change_key_case(getenv(), CASE_UPPER);
		}

		if($config_order[0] == "env"){
			$config_final_data=array_merge($config_env_data, $config_file_data);
		}else{
			$config_final_data=array_merge($config_file_data, $config_env_data);
		}

		foreach ($config_final_data as $thisconfig_key => $thisconfig_value) {
			$thisconfig_key = strtoupper($thisconfig_key);
			if (!in_array($thisconfig_key, $config_variables)) {
				continue;
			}
			self::$vars[$thisconfig_key] = $thisconfig_value;
		}

		return true;
	}
}

?>