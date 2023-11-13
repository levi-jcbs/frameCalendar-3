<?php

/* ENGINE > FRONTEND > ENTRYPOINT */

class engine_frontend_entrypoint
{
	# Entrypoint function
	public static function run($post_overwrites = array())
	{
		self::overwrite_post_data($post_overwrites);

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

		if (!engine_frontend_user::init()) {
			return false;
		}

		if (!engine_frontend_request::init()) {
			return false;
		}

		return engine_frontend_render::run();
	}


	private static function overwrite_post_data($post_overwrites = array()): void
	{
		foreach ($post_overwrites as $overwrite_key => $overwrite_value) {
			$_POST[$overwrite_key] = $overwrite_value;
		}
	}
}

?>