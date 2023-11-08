<?php

/* APPLICATION > FRONTEND > PAGES > ADMIN > CALENDARS > DEFAULT */

class application_frontend_pages_admin_calendars_default
{
	public static function run()
	{
		$data = array();
			
		return engine_frontend_genhtml::run($data, "/application/code/frontend/pages/admin/calendars/default/screen.template.html");
	}

	public static function title()
	{
		return "Calendars";
	}
}

?>