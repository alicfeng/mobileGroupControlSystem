<?php
/**
 * Created by PhpStorm   User: AlicFeng   DateTime: 18-11-22 上午9:52
 */

namespace App\Common\Command;
class AdbCmd
{
    // ADB path
    const ADB_PATH = '/usr/local/bin/adb';

    // 获取当前设备list
    const GET_DEVICES = self::ADB_PATH . ' devices | sed "1d"';

    // 获取设备信息
    const GET_DEVICE_INFO = self::ADB_PATH . ' -s {deviceNo} shell getprop ro.product.model';

    // adb basic
    const BASIC_ADB = self::ADB_PATH . ' ';

    // adb shell
    const ADB_SHELL = self::ADB_PATH . ' -s {deviceNo} shell';
}