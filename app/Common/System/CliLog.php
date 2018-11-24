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
     * @param mixed $message
     * @param boolean $exitFlag
     * @response       []
     */
    public static function info($message, $exitFlag = false)
    {
        Log::info($message);
        if ($exitFlag) {
            exit($message);
        } else {
            echo $message;
        }
    }
}