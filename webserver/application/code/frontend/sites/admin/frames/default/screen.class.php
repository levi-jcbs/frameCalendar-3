<?php

/* APPLICATION > FRONTEND > sites > ADMIN > FRAMES > DEFAULT */

class application_frontend_sites_admin_frames_default
{
	public static function run()
	{
		$data = array();
			
		return engine_frontend_genhtml::run($data, "/application/code/frontend/sites/admin/frames/default/screen.template.html");
	}

	public static function title()
	{
		return "Frames";
	}
}

?>