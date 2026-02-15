<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Global middleware...
     */

    /**
     * Route middleware groups...
     */


    /**
     * Route middleware (yang benar tempatnya di sini)
     */
    // app/Http/Kernel.php
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'admin.news' => \App\Http\Middleware\AdminMiddleware::class,
        'verified_worker' => \App\Http\Middleware\AdminMiddleware::class,
    ];


}
