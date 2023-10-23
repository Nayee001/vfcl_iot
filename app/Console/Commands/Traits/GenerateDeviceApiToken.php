<?php

namespace App\Console\Commands\Traits;
use Illuminate\Support\Str;

trait GenerateDeviceApiToken
{
    /**
     * Generate a unique API key.
     *
     * @return string
     */
    public static function generateApiKey()
    {
        return Str::random(50); // You can adjust the length as needed
    }
}