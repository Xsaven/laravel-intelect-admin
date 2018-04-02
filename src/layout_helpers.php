<?php

if (!function_exists('template')) {
    function template($text)
    {
        return "<div data-view=\"template\">{$text}</div>";
    }
}

if (!function_exists('resizer')) {
    function resizer()
    {
        return "<div data-view=\"resizer\"></div>";
    }
}