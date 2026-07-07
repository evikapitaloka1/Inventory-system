<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareThemeWithViews
{
    public function handle(Request $request, Closure $next): Response
    {
        View::share('currentTheme', $request->cookie('theme', 'light'));

        return $next($request);
    }
}
