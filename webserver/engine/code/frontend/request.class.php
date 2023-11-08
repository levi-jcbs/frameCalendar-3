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
		
		self::set_page();
		self::set_tab();
		self::set_screen();
		
		return true;
	}

	private static function import_sidemap()
	{
		$pages = json_decode(file_get_contents(PROJECT_ROOT . "/application/code/frontend/pages/pages.json"), true);

		foreach ($pages as $page_key => $page_value) {
			$tabs = json_decode(file_get_contents(PROJECT_ROOT . "/application/code/frontend/pages/" . $page_key . "/tabs.json"), true);

			foreach ($tabs as $tab_key => $tab_value) {
				$screens = json_decode(file_get_contents(PROJECT_ROOT . "/application/code/frontend/pages/" . $page_key . "/" . $tab_key . "/screens.json"), true);
				$tabs[$tab_key]["screens"] = $screens;
			}

			$pages[$page_key]["tabs"] = $tabs;
		}

		self::$sidemap = $pages;
	}

	private static function set_page()
	{
		if (
			array_key_exists("memory_page", $_POST) /* page muss im request mitgegeben worden sein */
			and array_key_exists($_POST["memory_page"], self::$sidemap) /* Page muss existieren */
		) {
			self::$page = $_POST["memory_page"];
		} else {
			foreach (self::$sidemap as $thispage_key => $thispage_vaue) { /* durchsuche pages nach default */
				if ($thispage_vaue["default"] === true) {
					self::$page = $thispage_key;
					break;
				}
			}
		}
	}

	private static function set_tab()
	{
		if (
			array_key_exists("memory_tab", $_POST) /* tab muss im request mitgegeben worden sein */
			and array_key_exists($_POST["memory_tab"], self::$sidemap[self::$page]["tabs"]) /* tab muss existieren */
			and self::$sidemap[self::$page]["tabs"][$_POST["memory_tab"]]["authentication"] === engine_global_user::$authenticated /* entweder beides authed oder keins von beiden */
		) {
			self::$tab = $_POST["memory_tab"];
		} else {
			foreach (self::$sidemap[self::$page]["tabs"] as $thistab_key => $thistab_vaue) { /* durchsuche tabs nach default */
				if (
					$thistab_vaue["default"] === true
					and $thistab_vaue["authentication"] === engine_global_user::$authenticated /* entweder beides auth oder keins von beiden */
				) {
					self::$tab = $thistab_key;
					break;
				}
			}
		}
	}

	private static function set_screen()
	{
		if (
			array_key_exists("memory_screen", $_POST) /* screen muss im request mitgegeben worden sein */
			and array_key_exists($_POST["memory_screen"], self::$sidemap[self::$page]["tabs"][self::$tab]["screens"]) /* screen muss existieren */
		) {
			self::$screen = $_POST["memory_screen"];
		} else {
			foreach (self::$sidemap[self::$page]["tabs"][self::$tab]["screens"] as $thisscreen_key => $thisscreen_value) { /* durchsuche screens nach default */
				if ($thisscreen_value["default"] === true) {
					self::$screen = $thisscreen_key;
					break;
				}
			}
		}
	}
}

?>