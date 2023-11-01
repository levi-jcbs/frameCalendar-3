<?php

/* ENGINE > FRONTEND > RENDER */

class engine_frontend_render
{

	public static function run()
	{
		# Site Data
		$data = array();

		$data = self::import_globals($data);

		return engine_frontend_genhtml::run($data, "/application/code/frontend/base/base.template.html");
	}

	public static function import_globals($data)
	{
		$request_path = dirname($_SERVER["REQUEST_URI"]);
		$script_path = dirname($_SERVER["SCRIPT_NAME"]);
		
		$data["global_project_root"]=substr($request_path, 0, strrpos($request_path, $script_path));
		
		return $data;
	}
}

?>