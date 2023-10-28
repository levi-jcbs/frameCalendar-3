<?php

class engine_global_import
{
    private static $components = [
        "null",
        "frontend",
        "backend"
    ];

    public static function run($component = null)
    {
        if (!in_array($component, self::$components)) {
            return [
                false,
                "component_not_found",
                "Requested Component doesn't exist."
            ];
        }

        self::engine($component);
        self::application($component);
    }

    private static function engine($component)
    {
        self::require_once_recursive($_SERVER["DOCUMENT_ROOT"]."/engine/code/global/");
        self::require_once_recursive($_SERVER["DOCUMENT_ROOT"]."/engine/code/".$component."/");
    }

    private static function application($component)
    {
        self::require_once_recursive($_SERVER["DOCUMENT_ROOT"]."/application/code/global/");
        self::require_once_recursive($_SERVER["DOCUMENT_ROOT"]."/application/code/".$component."/");
    }

    private static function require_once_recursive($directory)
    {
        if (is_dir($directory)) {
            $scan = scandir($directory);
            unset($scan[0], $scan[1]); //unset . and ..
            foreach ($scan as $file) {
                if (is_dir($directory . "/" . $file)) {
                    self::require_once_recursive($directory . "/" . $file);
                } else {
                    if (strpos($file, '.class.php') !== false) {
                        require_once($directory . "/" . $file);
                    }
                }
            }
        }
    }
}

?>