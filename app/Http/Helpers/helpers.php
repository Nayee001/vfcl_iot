<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

/**
 * Write code on Method
 *
 * @return response()
 */
if (!function_exists('convertYmdToMdy')) {
    function convertYmdToMdy($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
    }
}

/**
 * Write code on Method
 *
 * @return response()
 */
if (!function_exists('convertMdyToYmd')) {
    function convertMdyToYmd($date)
    {
        return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
    }
}

if (!function_exists('statusMessage')) {
    function statusMessage($status_code)
    {
        switch ((int)$status_code) {
            case 200:
                return 'OK(200)';
                break;
            case 400:
                return 'BAD_REQUEST(400)';
                break;
            case 401:
                return 'UNAUTHORIZED(401)';
                break;
            case 404:
                return 'NOT_FOUND(404)';
                break;
            case 405:
                return 'METHOD_NOT_ALLOWED(405)';
                break;
            case 429:
                return 'TOO_MANY_REQUEST(429)';
                break;
            case 500:
                return 'INTERNAL_ERROR(500)';
                break;
            default:
                return 'UNKNOWN(' . $status_code . ')';
                break;
        }
    }
}

if (!function_exists('interval')) {
    function interval($interval = null)
    {
        $threshold = match ($interval) {
            10 => 10, // 10 minutes
            15 => 15, // 15 minutes
            30 => 30, // 30 minutes
            60 => 60, // 1 hour
            default => 1, // Default to 1 minute
        };
        return $threshold;
    }
}

if (!function_exists("errorMessage")) {
    function errorMessage()
    {
        return response()->json(['code' => statusMessage(400), 'Message' => __('messages.error')]);
    }
}

if (!function_exists("successMessage")) {
    function successMessage($message)
    {
        return response()->json(['code' => statusMessage(200), 'Message' => $message]);
    }
}
if (!function_exists("exceptionMessage")) {
    function exceptionMessage($message)
    {
        return response()->json(['code' => statusMessage(500), 'Message' => $message]);
    }
}

if (!function_exists("generateRandomPassword")) {
    function generateRandomPassword($length)
    {
        $randomString = Str::random($length);
        return $randomString;
    }
}

if (!function_exists("role")) {
    function role()
    {
        return implode(', ', Auth::user()->roles->pluck('name')->toArray());
    }
}

if (!function_exists("roleChecker")) {
    function roleChecker()
    {
        $ROLES = [
            'Super Admin' => 1,
            'Manager' => 2,
            'Customer' => 3,
        ];
        return $ROLES;
    }
}
if (!function_exists("disabledIfNotSuperAdmin")) {
    function disabledIfNotSuperAdmin()
    {
        $disable = '';
        $targetValue = (int)implode(', ', Auth::user()->roles->pluck('id')->toArray());
        if ($targetValue == roleChecker()['Manager']) {
            $disable = 'disabled';
        } else {
            $disable = '';
        }
        return $disable;
    }
}


if (!function_exists("isSuperAdmin")) {
    function isSuperAdmin()
    {
        $targetValue = (int)implode(', ', Auth::user()->roles->pluck('id')->toArray());
        if ($targetValue ==  roleChecker()['Super Admin']) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists("isManager")) {
    function isManager()
    {
        $targetValue = (int)implode(', ', Auth::user()->roles->pluck('id')->toArray());
        if ($targetValue ==  roleChecker()['Manager']) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists("isCustomer")) {
    function isCustomer()
    {
        $targetValue = (int)implode(', ', Auth::user()->roles->pluck('id')->toArray());
        if ($targetValue ==  roleChecker()['Customer']) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists("permissionChecker")) {
    function permissionChecker()
    {
        $permissionNamesArray = [
            1 => 'list',
            2 => 'create',
            3 => 'edit',
            4 => 'delete',
        ];

        return $permissionNamesArray;
    }
}

if (!function_exists('dynamicRedAsterisk')) {
    /**
     * Generate a dynamic red asterisk string based on a specified length.
     *
     * @param int $length
     * @return string
     */
    function dynamicRedAsterisk($length = 1)
    {
        $redAsterisk = '<span style="color: red;">' . str_repeat('*', $length) . '</span>';
        return $redAsterisk;
    }
}

if (!function_exists('getRandomBackgroundColor')) {
    function getRandomBackgroundColor()
    {
        $backgroundColors = ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-dark'];

        // Get a random index from the array
        $randomIndex = array_rand($backgroundColors);

        // Return the selected background color class
        return $backgroundColors[$randomIndex];
    }
}
