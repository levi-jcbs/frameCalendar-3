<?php

/* APPLICATION > FRONTEND > sites > ADMIN > SETTINGS > DEFAULT */

class application_frontend_sites_admin_settings_default
{
	public static function run()
	{
		$data = array();
			
		return engine_frontend_genhtml::run($data, "/application/code/frontend/sites/admin/settings/default/screen.template.html");
	}
	
	public static function title(){
		return "Preferences";
	}
}

?>