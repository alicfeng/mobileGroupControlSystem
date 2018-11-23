<?php
/**
 * Created by PhpStorm   User: AlicFeng   DateTime: 18-11-22 上午9:49
 */

namespace App\Service;

use App\Common\Command\AdbCmd;
use App\Common\Constant\Strings;

class DeviceService
{
    /**
     * @functionName   获取设备序列号
     * @description    获取设备序列号
     * @version        v1.0.0
     * @author         Alicfeng
     * @datetime       18-11-22 下午3:58
     * @return array|bool
     * @response       []
     */
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
            array_push($deviceList, trim(str_replace(['device', ' '], ['', ''], $device)));
        }

        unset($result, $status);
        return $deviceList;
    }


    public function devicesInfo($devicesNo = [])
    {
        if (!is_array($devicesNo)) {
            return false;
        }
        $messages = [];
        foreach ($devicesNo as $deviceNo) {
            $command = str_replace('{deviceNo}', $deviceNo, AdbCmd::GET_DEVICE_INFO);
            exec($command, $result, $status);
            if (0 != $status) continue;
            array_push($messages, ['deviceNo' => $deviceNo, 'product' => trim($result[0])]);
        }
        return $messages;
    }
}