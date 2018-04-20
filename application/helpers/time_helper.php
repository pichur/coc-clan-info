<?php

if (!function_exists('logFolderDateTime')) {
    
    /**
     * @param $year
     * @param $month
     * @param $day
     * @param $time
     * @return DateTime
     */
    function logFolderDateTime ($year, $month, $day, $time) {
        $dateTime = new DateTime();
        $dateTime->setDate($year, $month, $day);
        $times = explode('-', $time);
        $dateTime->setTime($times[0], $times[1], $times[2]);
        return $dateTime;
    }
    
}
