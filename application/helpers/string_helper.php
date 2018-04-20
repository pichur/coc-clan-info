<?php

if (!function_exists('debug')) {
    function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
}

if (!function_exists('debug')) {
    function endsWith($haystack, $needle) {
        $length = strlen($needle);
        
        return $length === 0 ||
        (substr($haystack, -$length) === $needle);
    }
}
