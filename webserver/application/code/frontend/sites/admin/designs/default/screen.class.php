<?php

/* APPLICATION > FRONTEND > sites > ADMIN > DESIGNS > DEFAULT */

class application_frontend_sites_admin_designs_default
{
	public static function run()
	{
		$data = array();
			
		return engine_frontend_genhtml::run($data, "/application/code/frontend/sites/admin/designs/default/screen.template.html");
	}

	public static function title()
	{
		return "Designs";
	}
}

?>