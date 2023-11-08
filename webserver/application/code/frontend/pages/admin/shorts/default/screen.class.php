<?php

/* APPLICATION > FRONTEND > PAGES > ADMIN > SHORTS > DEFAULT */

class application_frontend_pages_admin_shorts_default
{
	public static function run()
	{
		$data = array();
			
		return engine_frontend_genhtml::run($data, "/application/code/frontend/pages/admin/shorts/default/screen.template.html");
	}

	public static function title()
	{
		return "Shorts";
	}
}

?>