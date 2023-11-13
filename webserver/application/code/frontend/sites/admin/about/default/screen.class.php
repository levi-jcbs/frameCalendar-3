<?php

/* APPLICATION > FRONTEND > sites > ADMIN > ABOUT > DEFAULT */

class application_frontend_sites_admin_about_default
{
	public static function run()
	{
		$data = array();
			
		return engine_frontend_genhtml::run($data, "/application/code/frontend/sites/admin/about/default/screen.template.html");
	}

	public static function title()
	{
		return "About";
	}
}

?>