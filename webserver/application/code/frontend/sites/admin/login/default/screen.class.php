<?php

/* APPLICATION > FRONTEND > sites > ADMIN > LOGIN > DEFAULT */

class application_frontend_sites_admin_login_default
{
	public static function run()
	{
		$data = array();

		$data = self::set_login_text($data);

		return engine_frontend_genhtml::run($data, "/application/code/frontend/sites/admin/login/default/screen.template.html");
	}

	public static function title()
	{
		return "Sign in";
	}

	private static function set_login_text(array $data = [])
	{
		if (engine_global_appconfig::$password_hashed === null) {
			$data["text_enter_password"] = "Please set a new password:";
			$data["text_submit_password"] = "set password";
		} else {
			$data["text_enter_password"] = "Please enter your password:";
			$data["text_submit_password"] = "sign in";
		}

		return $data;
	}
}

?>