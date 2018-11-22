<?php
/**
 * Created by PhpStorm   User: AlicFeng   DateTime: 18-11-22 上午9:49
 */

namespace App\Service;

use App\Common\Command\AdbCmd;
use App\Common\Constant\Strings;

class DeviceService
{
    public function deviceNoList()
    {
        $deviceList = [];
        exec(AdbCmd::GET_DEVICES, $result, $status);
        if (0 != $status) {
            return false;
        }
        // 设备去重
        $result = array_unique($result);
        // 重组设备
        foreach ($result as $device) {
            if (Strings::NULL == $device) continue;
            array_push($deviceList, trim(str_replace(['device', ' '], ['', ''],$device)));
        }

        unset($result, $status);
        return $deviceList;
    }
}