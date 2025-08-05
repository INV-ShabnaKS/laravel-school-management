<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {  
        if ($request->is('api/*')) {
            if ($e instanceof UnauthorizedHttpException) {
                return response()->json(['error' => 'Token is invalid or expired'], 401);
            }

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }

        return parent::render($request, $e);
    }
}

