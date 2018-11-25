<?php
/**
 * Created by AlicFeng in 2017/12/19 11:21
 */

namespace App\Utils;

class DateUtil
{
    /**
     * 获取毫秒的时间戳
     * @return mixed
     */
    public static function getMicroTime()
    {
        $time  = explode(' ', microtime());
        $time  = $time [1] . ($time [0] * 1000);
        $time2 = explode('.', $time);
        return $time2 [0];
    }

    /**
     * 获取年龄
     * @param int $birthdayTime 出生日时间戳
     * @param int $referTime 参照时间戳
     * @return int
     */
    public static function getAge($birthdayTime, $referTime)
    {
        list($y1, $m1, $d1) = explode('-', date('Y-m-d', $birthdayTime));
        list($y2, $m2, $d2) = explode('-', date('Y-m-d', $referTime));
        $age = $y2 - $y1;
        if ((int)($m2 . $d2) < (int)($m1 . $d1)) $age -= 1;
        return $age;
    }

    public static function microTime()
    {
        list($usec, $sec) = explode(' ', microtime());
        return intval($usec*1000*1000 + $sec*1000*1000);
    }
}