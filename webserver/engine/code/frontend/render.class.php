<?php

/* ENGINE > FRONTEND > RENDER */

class engine_frontend_render
{

	public static function run()
	{
		# Site Data
		$data = array();

		$data = self::set_globals($data);
		$data = self::set_menu($data);
		$data = self::set_notifications($data);
		$data = self::set_memories($data);

		# Render Screen
		$data["html_main"] = call_user_func([
			"application_frontend_pages_admin_" . engine_frontend_request::$tab . "_" . engine_frontend_request::$screen,
			"run"
		]);

		$data["text_html_title"] = engine_frontend_request::$sidemap[engine_frontend_request::$page]["html-title"] . " | " . call_user_func([
			"application_frontend_pages_admin_" . engine_frontend_request::$tab . "_" . engine_frontend_request::$screen,
			"title"
		]);

		# Render 

		return engine_frontend_genhtml::run($data, "/application/code/frontend/base/base.template.html");
	}

	public static function set_globals($data)
	{
		$request_path = dirname($_SERVER["REQUEST_URI"]);
		$script_path = dirname($_SERVER["SCRIPT_NAME"]);

		$data["propertie_global_project_root"] = substr($request_path, 0, strrpos($request_path, $script_path));

		return $data;
	}

	private static function set_menu($data)
	{
		$menu_elements = array();
		foreach (engine_frontend_request::$sidemap[engine_frontend_request::$page]["tabs"] as $tab => $properties) {
			if ($properties["authentication"] !== engine_global_user::$authenticated) {
				continue;
			}

			if (engine_frontend_request::$tab == $tab) {
				$class_tab_active = "_active";
			} else {
				$class_tab_active = "";
			}

			/* durchsuche screens nach default */
			foreach (engine_frontend_request::$sidemap[engine_frontend_request::$page]["tabs"][$tab]["screens"] as $thisscreen_key => $thisscreen_vaue) {
				if ($thisscreen_vaue["default"] === true) {
					$propertie_load_screen = $thisscreen_key;
					break;
				}
			}

			$menu_elements[] = [
				"class_tab_active" => $class_tab_active,
				"propertie_load_tab" => $tab,
				"propertie_load_screen" => $propertie_load_screen,
				"text_tab_name" => $properties["menu-title"]
			];
		}

		$html_menu = engine_frontend_genhtml::snippet($menu_elements, "/application/code/frontend/base/menu.snippet.html");
		$data["html_menu"] = $html_menu;

		return $data;
	}

	private static function set_notifications($data)
	{
		$snippet_data = array();
		$count = 0;
		foreach (engine_frontend_notification::$notifications as $notification) {
			$snippet_data[] = [
				"class_notification_type" => $notification["type"],
				"text_notification_content" => $notification["content"],
				"propertie_notification_delay" => (($count * 1250) + 200) . "ms"
			];
			$count++;
		}

		$html_notifications = engine_frontend_genhtml::snippet($snippet_data, "/application/code/frontend/base/notification.snippet.html");

		$data["html_notifications"] = $html_notifications;

		return $data;
	}

	private static function set_memories($data)
	{
		$data["hidden_memory_page"] = engine_frontend_request::$page;
		$data["hidden_memory_tab"] = engine_frontend_request::$tab;
		$data["hidden_memory_screen"] = engine_frontend_request::$screen;
		$data["hidden_memory_query"] = htmlspecialchars(json_encode(engine_frontend_request::$query));
		$data["hidden_memory_password"] = engine_global_user::$password;

		return $data;
	}

}

?>