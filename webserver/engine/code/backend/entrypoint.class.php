<?php

/* ENGINE > BACKEND > ENTRYPOINT */

class engine_backend_entrypoint
{
	# Entrypoint function
	public static function run($get_overwrites = array())
	{
		self::overwrite_get_data($get_overwrites);

		engine_global_helper::set_content_type("json");

		if (!engine_global_manifest::init()) {
			return false;
		}

		if (!engine_global_sysconfig::init()) {
			return false;
		}

		if (!engine_global_database::connect()) {
			return false;
		}

		if (!engine_global_appconfig::init()) {
			return false;
		}

		if (!engine_backend_user::init()) {
			return false;
		}

		if (!engine_backend_request::init()) {
			return false;
		}

		return engine_backend_call::run();
	}

	private static function overwrite_get_data($get_overwrites = array()): void
	{
		foreach ($get_overwrites as $overwrite_key => $overwrite_value) {
			$_GET[$overwrite_key] = $overwrite_value;
		}
	}
}

?>