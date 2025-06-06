<?php

if (!function_exists('display_time')) {
    function display_time($datetime, $format = 'j F Y', $timezone = 'Australia/Melbourne')
    {
        return ($datetime->diffInDays(now()) < 1) ?
            $datetime->diffForHumans() :
            $datetime->timezone($timezone)->format($format);
    }
}
