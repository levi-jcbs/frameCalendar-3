<?php

require_once("../../../engine/import/frontend.php");

engine_frontend_entrypoint::run(["page" => "admin"]); # Runs entrypoint of frontend (and overrides page to admin)

?>