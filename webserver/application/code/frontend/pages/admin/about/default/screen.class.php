<?php

/* APPLICATION > FRONTEND > PAGES > ADMIN > ABOUT > DEFAULT */

class application_frontend_pages_admin_about_default
{
	public static function run()
	{
		$data = array();
			
		return engine_frontend_genhtml::run($data, "/application/code/frontend/pages/admin/about/default/screen.template.html");
	}

	public static function title()
	{
		return "About";
	}
}

?>