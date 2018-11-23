<?php
/**
 * Created by PhpStorm   User: AlicFeng   DateTime: 18-11-23 下午6:36
 */

namespace App\Common\System;

use Log;

class CliLog
{
    /**
     * @functionName   CliLog
     * @description    cli model log
     * @version        v1.0.0
     * @author         Alicfeng
     * @datetime       18-11-23 下午6:46
     * @param $message
     * @response       []
     */
    public static function info($message)
    {
        echo $message;
        Log::info($message);
    }
}