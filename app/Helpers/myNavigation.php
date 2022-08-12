<?php

use App\Models\Navigation;

if (!function_exists('myNavigation')) {
    function myNavigation()
    {
        return Navigation::with('children')->whereNull('parent_id')->get();
    }
}
