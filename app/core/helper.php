<?php
use App\Entitlement\Model\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use function GuzzleHttp\json_encode;

// This function return success status
if (!function_exists('pStatusSuccess')) {

    /**
     * Returns a status string
     *
     * @return string
     *
     */
    function pStatusSuccess()
    {
        return 'SUCCESS';
    }
}

// This function return failed status
if (!function_exists('pStatusFailed')) {

    /**
     * Returns a status string
     *
     * @return string
     *
     */
    function pStatusFailed()
    {
        return 'FAILED';
    }
}
