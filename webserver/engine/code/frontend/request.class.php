<?php

/* ENGINE > FRONTEND > REQUEST */

class engine_frontend_request
{
	public static $page;
	public static $tab;
	public static $screen;
	public static $options = array();
	public static $sitemap = array();

	public static function init()
	{
		self::import_sitemap();

		self::set_page();
		self::set_tab();
		self::set_screen();
		self::set_options();

		return true;
	}

	private static function import_sitemap()
	{
		$pages = json_decode(file_get_contents(PROJECT_ROOT . "/application/code/frontend/sites/pages.json"), true);

		foreach ($pages as $page_key => $page_value) {
			$tabs = json_decode(file_get_contents(PROJECT_ROOT . "/application/code/frontend/sites/" . $page_key . "/tabs.json"), true);

			foreach ($tabs as $tab_key => $tab_value) {
				$screens = json_decode(file_get_contents(PROJECT_ROOT . "/application/code/frontend/sites/" . $page_key . "/" . $tab_key . "/screens.json"), true);
				$tabs[$tab_key]["screens"] = $screens;
			}

			$pages[$page_key]["tabs"] = $tabs;
		}

		self::$sitemap = $pages;
	}

	private static function set_page()
	{
		if (
			array_key_exists("memory_page", $_POST) /* page muss im request mitgegeben worden sein */
			and array_key_exists($_POST["memory_page"], self::$sitemap) /* Page muss existieren */
		) {
			self::$page = $_POST["memory_page"];
		} else {
			foreach (self::$sitemap as $thispage_key => $thispage_vaue) { /* durchsuche pages nach default */
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
			and array_key_exists($_POST["memory_tab"], self::$sitemap[self::$page]["tabs"]) /* tab muss existieren */
			and self::$sitemap[self::$page]["tabs"][$_POST["memory_tab"]]["authentication"] === engine_global_user::$authenticated /* entweder beides authed oder keins von beiden */
		) {
			self::$tab = $_POST["memory_tab"];
		} else {
			foreach (self::$sitemap[self::$page]["tabs"] as $thistab_key => $thistab_vaue) { /* durchsuche tabs nach default */
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
			and array_key_exists($_POST["memory_screen"], self::$sitemap[self::$page]["tabs"][self::$tab]["screens"]) /* screen muss existieren */
		) {
			self::$screen = $_POST["memory_screen"];
		} else {
			foreach (self::$sitemap[self::$page]["tabs"][self::$tab]["screens"] as $thisscreen_key => $thisscreen_value) { /* durchsuche screens nach default */
				if ($thisscreen_value["default"] === true) {
					self::$screen = $thisscreen_key;
					break;
				}
			}
		}
	}

	private static function set_options()
	{
		if (
			array_key_exists("memory_options", $_POST) /* options müssen im request mitgegeben worden sein */
		) {
			$request_options = json_decode(engine_global_helper::get_post_var("memory_options"), true);

			foreach (self::$sitemap[self::$page]["tabs"][self::$tab]["screens"][self::$screen]["options"] as $option) {
				if (array_key_exists($option, $request_options)) {
					self::$options[$option] = $request_options[$option];
				}
			}
		}

		return true;
	}

}

?>