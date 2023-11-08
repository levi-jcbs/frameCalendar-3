<?php

/* ENGINE > FRONTEND > NOTIFICATION */

class engine_frontend_notification
{
	public static $notifications = array();

	/* PUBLIC METHODS */
	public static function new($type, $content)
	{
		if ($type != "positive" and $type != "neutral" and $type != "negative") {
			return false;
		}

		$content = htmlspecialchars($content);

		$notification = [
			"type" => $type,
			"content" => $content
		];

		self::$notifications[] = $notification;
		
		return true;
	}
}
?>