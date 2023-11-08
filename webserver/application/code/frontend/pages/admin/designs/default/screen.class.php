<?php

/* APPLICATION > FRONTEND > PAGES > ADMIN > DESIGNS > DEFAULT */

class application_frontend_pages_admin_designs_default
{
	public static function run()
	{
		$data = array();
			
		return engine_frontend_genhtml::run($data, "/application/code/frontend/pages/admin/designs/default/screen.template.html");
	}

	public static function title()
	{
		return "Designs";
	}
}

?>