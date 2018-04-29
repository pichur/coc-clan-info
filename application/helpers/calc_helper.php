<?php

if (!function_exists('avg')) {
    function avg ($lastAverage, $newValue, $count) {
        return (($lastAverage * $count) + $newValue) / ($count + 1);
    }
}
