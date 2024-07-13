<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Pusher\Pusher;

class PusherServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('pusher', function ($app) {
            $options = [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
                'curl_options' => [
                    CURLOPT_CAINFO => base_path('path/to/cacert.pem'), // Update the path accordingly
                ],
            ];

            return new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
