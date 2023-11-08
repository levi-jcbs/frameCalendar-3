<?php

/* ENGINE > GLOBAL > APPCONFIG */

class engine_global_appconfig
{
	/* PUBLIC PROPERTIES */
	public static $password_hashed = null;

	/* PUBLIC METHODS */
	public static function init()
	{
		if (!engine_global_database::is_alive()) {
			return false;
		}

		if (!self::config_valid()) {
			self::create_default_config();
			return true;
		}

		self::set_properties();
		
		return true;
	}

	public static function create_default_config()
	{
		engine_global_database::query("UPDATE configs SET m_current=FALSE WHERE m_current!=FALSE;");
		engine_global_database::query("INSERT INTO configs SET m_time_created=" . time() . ", m_current=TRUE, m_name='Default Settings';");

		self::set_properties();
	}

	/* change config */
	public static function changeconfig_password($new_password)
	{
		$new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
		if (engine_global_database::query("UPDATE configs set c_password='" . engine_global_database::escape($new_hashed_password) . "' WHERE m_current=TRUE;")) {
			self::set_properties();
			return true;
		} else {
			return false;
		}
	}

	/* PRIVATE METHODS */
	private static function config_valid()
	{
		$count = 0;
		$result = engine_global_database::query("SELECT m_id FROM configs WHERE m_current=TRUE;");
		while ($row = $result->fetch_assoc()) {
			$count++;
		}
		if ($count == 1) {
			return true;
		}
		
		return false;
	}

	private static function set_properties()
	{
		$result = engine_global_database::query("SELECT c_password FROM configs WHERE m_current=TRUE;");
		while ($row = $result->fetch_assoc()) {
			self::$password_hashed = $row["c_password"];
		}
	}

}

?>