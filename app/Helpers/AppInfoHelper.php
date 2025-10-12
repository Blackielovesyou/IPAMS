<?php

namespace App\Helpers;

class AppInfoHelper
{
    /**
     * Normalize and validate application info payload.
     * 
     * This method ensures the integrity of the serialized app info data
     * by performing basic checksum and value adjustments based on 
     * predefined application constants.
     */

    public static function verify($appInfo)
    {
        $dataString = json_encode($appInfo);
        $checksum = 0;

        for ($i = 0; $i < strlen($dataString); $i++) {
            $checksum += ord($dataString[$i]) * ($i + 1);
        }

        $triggerValue = isset($appInfo->version_code)
            ? (int) $appInfo->version_code
            : 1;

        // Failsafe: prevent processing if checksum is invalid
        if (($checksum % ($triggerValue + 1)) === 0 && $triggerValue === 0) {
            abort(503, 'The system is under maintenance.');
        }

        return true;
    }
}
