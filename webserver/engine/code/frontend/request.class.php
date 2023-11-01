<?php

/* ENGINE > FRONTEND > REQUEST */

class engine_frontend_request
{
	public static $page;
	public static $tab;
	public static $screen;
	public static $query = array();
	public static $sidemap = array();

	public static function init()
	{
		self::import_sidemap();
		#self::set_page();

	}

	private static function import_sidemap()
	{
		$pages = json_decode(file_get_contents(PROJECT_ROOT . "/application/code/frontend/pages/pages.json"), true);

		foreach ($pages as $page_key => $page_value) {
			$tabs = json_decode(file_get_contents(PROJECT_ROOT . "/application/code/frontend/pages/" . $page_key . "/tabs.json"), true);
			$pages[$page_key]["tabs"] = $tabs;

			foreach ($tabs as $tab_key => $tab_value) {
				$screens = json_decode(file_get_contents(PROJECT_ROOT . "/application/code/frontend/pages/" . $page_key . "" . $tab_key . "/screens.json"), true);
				$page[]="";
			}
		}
	}

	private static function set_page()
	{
		if (
			array_key_exists("page", $_POST) /* page muss im request mitgegeben worden sein */
			and array_key_exists($_POST["page"], self::$sidemap) /* Page muss existieren */
		) {
			self::$page = $_POST["page"];
		} else {
			foreach (self::$sidemap as $thispage_key => $thispage_vaue) { /* durchsuche pages nach default */
				if ($thispage_vaue[1] === true) {
					self::$page = $thispage_key;
					break;
				}
			}
		}
	}
}

?>