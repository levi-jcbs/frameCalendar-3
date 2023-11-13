<?php

/* ENGINE > BACKEND > USER   */
/* extends engine_global_user */

class engine_backend_user extends engine_global_user
{
	public static function init()
	{
		self::authenticate();

		return true;
	}

	private static function authenticate()
	{
		/* Exit when password not set */
		if (engine_global_appconfig::$password_hashed === null) {
			return false;
		}

		$call_password = engine_global_helper::get_get_var("password");

		if ($call_password != "") {
			$password_plain = $call_password;
		} else {
			return false;
		}

		if (password_verify($password_plain, engine_global_appconfig::$password_hashed)) {
			engine_global_user::$authenticated = true;
			engine_global_user::$password = $password_plain;
			
			return true;
		} else {
			return false;
		}
	}
}