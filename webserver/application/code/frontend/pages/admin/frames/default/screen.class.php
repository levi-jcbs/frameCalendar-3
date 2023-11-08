<?php

/* APPLICATION > FRONTEND > PAGES > ADMIN > FRAMES > DEFAULT */

class application_frontend_pages_admin_frames_default
{
	public static function run()
	{
		$data = array();
			
		return engine_frontend_genhtml::run($data, "/application/code/frontend/pages/admin/frames/default/screen.template.html");
	}

	public static function title()
	{
		return "frames";
	}
}

?>