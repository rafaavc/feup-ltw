<?php

require_once("./control/config.php");
import(__FILE__, "control/router.php");

if (!Router\handle()) return false;

?>
