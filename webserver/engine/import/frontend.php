<?php

$project_root = realpath(__DIR__ . "/../..");

require_once($project_root . "/engine/code/global/import.class.php");
engine_global_import::run("frontend");

?>