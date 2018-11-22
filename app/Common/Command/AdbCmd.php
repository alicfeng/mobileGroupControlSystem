<?php
/**
 * Created by PhpStorm   User: AlicFeng   DateTime: 18-11-22 上午9:52
 */
namespace App\Common\Command;
class AdbCmd
{
    // 获取当前设备list
    const GET_DEVICES = 'adb devices | sed "1d"';
}