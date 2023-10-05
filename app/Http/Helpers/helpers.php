<?php

use Carbon\Carbon;
use Illuminate\Support\Str;

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

if(!function_exists("generateRandomPassword")){
    function generateRandomPassword($length)
    {
        $randomString = Str::random($length);
        return $randomString;
    }
}