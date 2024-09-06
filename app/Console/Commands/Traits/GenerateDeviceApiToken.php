<?php

namespace App\Console\Commands\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

trait GenerateDeviceApiToken
{
    /**
     * Generate a unique API key.
     *
     * @return string
     */
    public static function generateApiKey($macAddress)
    {
        //$salt = bin2hex(random_bytes(8)); // This will produce a string of 32 hexadecimal characters
        if ($macAddress) {
            // Now hash the MAC address with the salt using SHA-256
            $hashedMacAddress = substr(hash('sha256', $macAddress), 0, 20);
            return $hashedMacAddress;
        }
    }

    /**
     * Generate a unique API key of 6 hexadecimal characters.
     *
     * @return string
     */
    public static function generateShortApiKey()
    {
        $salt = bin2hex(random_bytes(3)); // This will produce a string of 6 hexadecimal characters
        return $salt;
    }
}
