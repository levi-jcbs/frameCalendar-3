<?php

/* APPLICATION > FRONTEND > PAGES > ADMIN > EVENTLISTS > DEFAULT */

class application_frontend_pages_admin_eventlists_default
{
	public static function run()
	{
		$data = array();
			
		return engine_frontend_genhtml::run($data, "/application/code/frontend/pages/admin/eventlists/default/screen.template.html");
	}

	public static function title()
	{
		return "Events";
	}
}

?>