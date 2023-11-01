<?php

/* ENGINE > FRONTEND > GENHTML */

class engine_frontend_genhtml
{
	public static function run($data = array(), $template)
	{
		$html_template = file_get_contents(PROJECT_ROOT . $template);

		$search_phrases = array();
		$replace_phrases = array();
		foreach ($data as $search => $replace) {
			$search_phrases[] = "%:" . strtoupper($search) . ":%";
			$replace_phrases[] = $replace;
		}

		return str_replace($search_phrases, $replace_phrases, $html_template);
	}

	public static function snippet($data_array = array(array()), $template)
	{
		$html = "";
		foreach ($data_array as $data) {
			$html .= self::run($data, $template);
		}

		return $html;
	}
}

?>