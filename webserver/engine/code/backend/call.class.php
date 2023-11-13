<?php

/* ENGINE > BACKEND > CALL */

class engine_backend_call
{
	public static function run()
	{
		$apiresponse = call_user_func([
			"application_backend_calls_" . engine_backend_request::$group . "_" . engine_backend_request::$object,
			engine_backend_request::$action
		]);

		if (!self::validate_apiresponse($apiresponse)) {
			return false;
		}

		return json_encode($apiresponse);
	}

	private static function validate_apiresponse($apiresponse)
	{
		if (!is_array($apiresponse)) {
			return false;
		} else {
			return true;
		}
	}
}

?>