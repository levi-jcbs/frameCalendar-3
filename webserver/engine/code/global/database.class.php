<?php

/* ENGINE > GLOBAL > DATABASE */

class engine_global_database
{
	private static $connection;

	#
	# PUBLIC METHODS
	#

	public static function connect($config = "sysconfig")
	{
		if ($config == "sysconfig") {
			$mariadb_host = engine_global_sysconfig::$vars[engine_global_manifest::$vars["database"]["credentials_sysconfig_variables"]["host"]];
			$mariadb_user = engine_global_sysconfig::$vars[engine_global_manifest::$vars["database"]["credentials_sysconfig_variables"]["user"]];
			$mariadb_password = engine_global_sysconfig::$vars[engine_global_manifest::$vars["database"]["credentials_sysconfig_variables"]["password"]];
			$mariadb_database = engine_global_sysconfig::$vars[engine_global_manifest::$vars["database"]["credentials_sysconfig_variables"]["database"]];
		} elseif (is_array($config)) {
			$mariadb_host = $config["host"];
			$mariadb_user = $config["user"];
			$mariadb_password = $config["password"];
			$mariadb_database = $config["database"];
		} else {
			return false;
		}

		try {
			self::$connection = new mysqli(
				$mariadb_host,
				$mariadb_user,
				$mariadb_password,
				$mariadb_database
			);
		} catch (mysqli_sql_exception $e){
			error_log($e->__toString());
			return false;
		}
		
		return self::is_alive();
	}

	public static function is_alive()
	{
		return self::$connection->ping();
	}

	public static function query($query)
	{
		return self::$connection->query($query);
	}

	public static function getFirstValue($query)
	{
		$result = self::query($query);
		$row = $result->fetch_row();

		return $row[0];
	}

	public static function escape($string)
	{
		return self::$connection->real_escape_string($string);
	}

	public static function close()
	{
		self::$connection->close();
		return true;
	}
}


?>