<?php

/* ENGINE > FRONTEND > REQUEST */

class engine_backend_request
{
	public static $group;
	public static $object;
	public static $action;
	public static $options = array();
	public static $callmap = array();

	public static function init()
	{
		self::import_callmap();

		if (!self::set_group()) {
			return false;
		}

		if (!self::set_object()) {
			return false;
		}

		if (!self::set_action()) {
			return false;
		}

		self::set_options();

		return true;
	}

	private static function import_callmap()
	{
		$groups = json_decode(file_get_contents(PROJECT_ROOT . "/application/code/backend/calls/groups.json"), true);

		foreach ($groups as $group_key => $group_value) {
			$objects = json_decode(file_get_contents(PROJECT_ROOT . "/application/code/backend/calls/" . $group_key . "/objects.json"), true);
			foreach ($objects as $object_key => $actions) {
				foreach ($actions as $action_key => $action_value) {
					if (!array_key_exists("authentication", $action_value)) {
						$action_value["authentication"] = $group_value["default_authentication"];
					}
					$actions[$action_key] = $action_value;
				}
				$objects[$object_key] = $actions;
			}

			$groups[$group_key]["objects"] = $objects;
		}

		self::$callmap = $groups;
	}

	private static function set_group()
	{
		if (
			array_key_exists("group", $_GET) /* Group muss im request mitgegeben worden sein */
			and array_key_exists($_GET["group"], self::$callmap) /* Group muss existieren */
		) {
			self::$group = $_GET["group"];
			return true;
		} else {
			return false;
		}
	}

	private static function set_object()
	{
		if (
			array_key_exists("object", $_GET) /* Object muss im request mitgegeben worden sein */
			and array_key_exists($_GET["object"], self::$callmap[self::$group]["objects"]) /* Object muss existieren */
		) {
			self::$object = $_GET["object"];
			return true;
		} else {
			return false;
		}
	}

	private static function set_action()
	{
		if (
			array_key_exists("action", $_GET) /* Action muss im request mitgegeben worden sein */
			and array_key_exists($_GET["action"], self::$callmap[self::$group]["objects"][self::$object]) /* Action muss existieren */
			and (
				self::$callmap[self::$group]["objects"][self::$object][$_GET["action"]]["authentication"] === false
				or self::$callmap[self::$group]["objects"][self::$object][$_GET["action"]]["authentication"] === engine_global_user::$authenticated
			)
		) {
			self::$action = $_GET["action"];
			return true;
		} else {
			return false;
		}
	}

	private static function set_options()
	{
		foreach (self::$callmap[self::$group]["objects"][self::$object][self::$action]["options"] as $option) {
			if (array_key_exists("o_" . $option, $_GET)) {
				self::$options[$option] = $_GET["o_" . $option];
			}
		}

		return true;
	}

}

?>