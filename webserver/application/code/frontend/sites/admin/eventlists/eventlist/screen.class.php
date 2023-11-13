<?php

/* APPLICATION > FRONTEND > sites > ADMIN > EVENTLISTS > EVENTLIST */

class application_frontend_sites_admin_eventlists_eventlist
{
	private static $name;

	public static function run()
	{
		self::set_properties();

		$data = array();

		$data["text_eventlist_name"] = htmlspecialchars(self::$name);

		return engine_frontend_genhtml::run($data, "/application/code/frontend/sites/admin/eventlists/eventlist/screen.template.html");
	}

	public static function title()
	{
		return "Eventlist ".self::$name;
	}

	private static function set_properties()
	{
		self::$name = engine_global_database::getFirstValue("SELECT m_name FROM eventlists WHERE m_id='" . engine_global_database::escape(engine_frontend_request::$options["id"]) . "';");
	}
}

?>