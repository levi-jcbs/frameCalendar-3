<?php

/* APPLICATION > FRONTEND > PAGES > ADMIN > SETTINGS > DEFAULT */

class application_frontend_pages_admin_settings_default
{
	public static function run()
	{
		$data = array();
			
		return engine_frontend_genhtml::run($data, "/application/code/frontend/pages/admin/settings/default/screen.template.html");
	}
	
	public static function title(){
		return "Preferences";
	}
}

?>