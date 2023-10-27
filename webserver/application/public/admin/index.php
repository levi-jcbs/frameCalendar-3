<?php

require_once("../../../engine/code/frontend/entrypoint.class.php");
engine_frontend_entrypoint::render(["page" => "admin"]); # Runs entrypoint of frontend (and overrides page to admin)

?>