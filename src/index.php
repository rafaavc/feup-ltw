<?php

require_once(dirname(__FILE__)."/control/config.php");
require_once(dirname(__FILE__)."/control/router.php");

if (!Router\handle()) return false;

?>