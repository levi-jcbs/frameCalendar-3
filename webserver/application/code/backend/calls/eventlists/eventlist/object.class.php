<?php

/* APPLICATION > BACKEND > CALLS > EVENTLISTS > EVENTLIST */

class application_backend_calls_eventlists_eventlist
{
	public static function create()
	{
		if (!engine_global_helper::array_field_usable("name", engine_backend_request::$options)) {
			return [
				"status" => "error",
				"description" => "Create eventlist: Please specify the name!",
				"target" => null
			];
		}

		if (strlen(engine_backend_request::$options["name"]) > 32) {
			return [
				"status" => "error",
				"description" => "Create eventlist: Please use maximum of 32 characters for the name!",
				"target" => null
			];
		}

		if (engine_global_database::query("INSERT INTO eventlists SET m_name='" . engine_global_database::escape(engine_backend_request::$options["name"]) . "', m_time_created='" . engine_global_database::escape(time()) . "';")) {
			$new_eventlist_id = engine_global_database::getFirstValue("SELECT LAST_INSERT_ID();");

			return [
				"status" => "success",
				"description" => "Eventlist successfully created",
				"target" => [
					"page" => "admin",
					"tab" => "eventlists",
					"screen" => "eventlist",
					"options" => [
						"id" => $new_eventlist_id
					]
				]
			];
		} else {
			return [
				"status" => "error",
				"description" => "Create eventlist: Database error.",
				"target" => null
			];
		}
	}
}

?>