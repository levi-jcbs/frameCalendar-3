<?php

/* ENGINE > FRONTEND > USER   */
/* extends engine_global_user */

class engine_frontend_user extends engine_global_user
{
	public static function init()
	{
		self::first_login_set_password(); /* Set password function for first login */
		self::authenticate();
		
		return true;
	}

	private static function first_login_set_password()
	{
		if (engine_global_appconfig::$password_hashed !== null) {
			return false;
		}

		if (engine_global_helper::get_post_var("login_password") == "") {
			return false;
		}
		
		$login_password = $_POST["login_password"];

		engine_frontend_notification::new("positive", "Successfully set password");
		return engine_global_appconfig::changeconfig_password($login_password);
	}

	private static function authenticate()
	{
		if (engine_global_appconfig::$password_hashed === null) {
			return false;
		}

		$login_password = engine_global_helper::get_post_var("login_password");
		$memory_password = engine_global_helper::get_post_var("memory_password");
		$new_login = engine_global_helper::get_post_var("new_login");

		if ($login_password != "" and isset($new_login)) { /* sign in button has to be pressed (it will be when by pressing enter) */
			$password_plain = $login_password;
			$new_login = true;
		} else if ($memory_password != "") {
			$password_plain = $memory_password;
			$new_login = false;
		} else {
			# User didnt try to login
			return false;
		}

		if (password_verify($password_plain, engine_global_appconfig::$password_hashed)) {
			engine_global_user::$authenticated = true;
			engine_global_user::$password = $password_plain;
			if ($new_login) {
				engine_frontend_notification::new("positive", "Successfully signed in");
			}
			return true;
		} else {
			engine_global_user::$authenticated = false;
			engine_global_user::$password = null;
			if ($new_login) {
				engine_frontend_notification::new("negative", "Sign in failed. Please try again");
			}
			return false;
		}
	}
}

?>