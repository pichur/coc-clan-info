<?php

if (!function_exists('avg')) {
    function avg ($lastAverage, $value, $count) {
        return (($lastAverage * $count) - $lastAverage + $value) / $count;
    }
}
