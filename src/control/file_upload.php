<?php

function isJPGImage($tmpPath) {
    return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $tmpPath) == 'image/jpeg';
}

?>

