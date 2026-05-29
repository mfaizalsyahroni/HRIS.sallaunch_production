<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    public function register()
    {
        $this->renderable(function (TokenMismatchException $e, $request) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Session expired'
                ], 419);
            }

            return redirect()->back()
                ->with('error', 'Session kamu sudah habis, silakan coba lagi.')
                ->withInput()
                ?: redirect('/')
                    ->with('error', 'Session habis, silakan login ulang');
        });
    }
}