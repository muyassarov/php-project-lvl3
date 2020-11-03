<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class HtmlHelper
{
    public static function activeClass(string $routeName, string $defaultClassName = 'active'): string
    {
        return Route::is($routeName) ? $defaultClassName : '';
    }

    public static function activeNavLabel(string $routeName): string
    {
        if (Route::is($routeName)) {
            return '<span class="sr-only">(current)</span>';
        }
        return '';
    }
}
