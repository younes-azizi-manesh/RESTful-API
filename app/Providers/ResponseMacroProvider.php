<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseMacroProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('jsonResponse', function(mixed $data, string $message, int $statusCode){
            return response()->json([
                'data' => $data,
                'message' => $message,
                'statusCode' => $statusCode
            ], $statusCode);
        });
    }
}
