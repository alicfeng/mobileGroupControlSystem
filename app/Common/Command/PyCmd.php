<?php
/**
 * Created by PhpStorm   User: AlicFeng   DateTime: 18-11-23 上午11:44
 */

namespace App\Common\Command;


class PyCmd
{
    const PLAYBOOK_PLAY = '/usr/bin/python ';

    public static function adbRecordPlay($playbook)
    {
        return base_path() . '/library/adb-event-record/adbrecord.py -p ' . $playbook;
    }
}