<?php

if (!function_exists('debug')) {
    function debug ($message) {
        $logThreshold = config_item('log_threshold');
        if (   (is_numeric($logThreshold) && ($logThreshold >= 2      ))
            || (is_array  ($logThreshold) && in_array(2, $logThreshold))) {
            log_message('debug', $message);
            echo $message . PHP_EOL;
        }
    }
}

if (!function_exists('info')) {
    function info ($message) {
        $logThreshold = config_item('log_threshold');
        if (   (is_numeric($logThreshold) && ($logThreshold >= 3      ))
            || (is_array  ($logThreshold) && in_array(3, $logThreshold))) {
                log_message('info', $message);
                echo $message . PHP_EOL;
            }
    }
}
