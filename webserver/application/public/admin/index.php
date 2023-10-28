<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/engine/import/frontend.php");

engine_frontend_entrypoint::run(["page" => "admin"]); # Runs entrypoint of frontend (and overrides page to admin)

?>