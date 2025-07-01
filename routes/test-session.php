<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-session', function () {
    return response()->json([
        'session_driver' => config('session.driver'),
        'session_domain' => config('session.domain'),
        'session_secure' => config('session.secure'),
        'session_http_only' => config('session.http_only'),
        'session_same_site' => config('session.same_site'),
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
    ]);
});
