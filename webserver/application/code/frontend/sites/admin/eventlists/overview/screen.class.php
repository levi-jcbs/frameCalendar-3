<?php

/* APPLICATION > FRONTEND > sites > ADMIN > EVENTLISTS > OVERVIEW */

class application_frontend_sites_admin_eventlists_overview
{
	public static function run()
	{
		$data = array();

		$data = self::set_overview($data);

		return engine_frontend_genhtml::run($data, "/application/code/frontend/sites/admin/eventlists/overview/screen.template.html");
	}

	public static function title()
	{
		return "Events";
	}

	private static function set_overview($data)
	{
		$overview_buttons_data = array();
		$result = engine_global_database::query("SELECT m_id, m_name FROM eventlists ORDER by m_name;");
		while ($row = $result->fetch_assoc()) {
			$this_button = array();
			$this_button["text_title"] = htmlspecialchars($row["m_name"]);
			$this_button["text_id"] = htmlspecialchars("# " . $row["m_id"]);
			$this_button["propertie_id"] = htmlspecialchars($row["m_id"]);

			$result2 = engine_global_database::query("SELECT COUNT(m_id) AS event_count FROM events WHERE m_eventlist=" . engine_global_database::escape($row["m_id"]) . ";");
			while ($row2 = $result2->fetch_assoc()) {
				$this_button["text_events"] = htmlspecialchars($row2["event_count"] . " events");
			}

			$overview_buttons_data[] = $this_button;
		}

		if (count($overview_buttons_data) === 0) {
			$data["text_empty_overview_notice"] = "There are no eventlists yet.";
		} else {
			$data["text_empty_overview_notice"] = "";
		}

		$data["html_overview_buttons"] = engine_frontend_genhtml::snippet($overview_buttons_data, "/application/code/frontend/sites/admin/eventlists/overview/overview_button.snippet.html");

		return $data;
	}
}

?>