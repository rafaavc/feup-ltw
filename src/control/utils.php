<?php

function elapsedTime($time) {
    $time = time() - $time; // to get the time since that moment
    $time = ($time < 1)? 1 : $time;
    $marks = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($marks as $mark => $text) {
        if ($time < $mark) continue;
        $markNo = floor($time / $mark);
        return $markNo.' '.$text.(($markNo > 1)? 's' : '');
    }
}

function getYearsAgo($years) {
    return strtotime(date("Y-m-d ")." -".$years." years");
}

function getArrayParameter($array, $name) {
    return isset($array[$name]) ? $array[$name] : null;
} 

function getArrayParameters($array, $names) {
    $res = array();
    foreach ($names as $name) {
        $value = getArrayParameter($array, $name);
        if ($value === null) return null;
        $res[$name] = $value;
    }
    return $res;
}

?>