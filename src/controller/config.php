<?php

function import($callingFile, $module) {
    require_once(dirname($callingFile).'/'.$module);
}

?>