<?php
/**
 * Created by PhpStorm   User: AlicFeng   DateTime: 18-11-23 上午11:44
 */

namespace App\Common\Command;


use App\Common\System\CliLog;

class PyCmd
{
    public static function adbRecordPlay($playbook, $device)
    {
        CliLog::info(base_path() . '/library/adb-event-record/adbrecord.py -p ' . $playbook . ' -t ' . $device);
        return base_path() . '/library/adb-event-record/adbrecord.py -p ' . $playbook . ' -t ' . $device;
    }
}