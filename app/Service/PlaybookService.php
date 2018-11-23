<?php
/**
 * Created by PhpStorm   User: AlicFeng   DateTime: 18-11-23 上午11:38
 */

namespace App\Service;


class PlaybookService
{
    public static function path($playbook)
    {
        return base_path() . '/playbook/' . $playbook . '.pb';
    }
}