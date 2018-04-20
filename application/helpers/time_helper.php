<?php

if (!function_exists('logFolderDateTime')) {
    
    /**
     * @param unknown $year
     * @param unknown $month
     * @param unknown $day
     * @param unknown $time
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
