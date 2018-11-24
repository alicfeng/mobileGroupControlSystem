<?php
/**
 * Created by AlicFeng in 2017/12/15 15:43
 */

namespace App\Utils;


class ObjectUtil
{
    /**
     * json字符串转对象 | 数组
     * @param $str
     * @param $assoc
     * @return mixed|boolean
     */
    public static function jsonDecode($str, $assoc = false)
    {
        if (self::isJson($str)) {
            return json_decode($str, $assoc);
        }
        return false;
    }

    /**
     * json字符串转数组
     * @param $str
     * @return mixed|boolean
     */
    public static function json2arr($str)
    {
        if (self::isJson($str)) {
            return json_decode($str, true);
        }
        return false;
    }

    /**
     * 判断string是否为json
     * @param $string
     * @return bool
     */
    public static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * 对象转数组
     * @param $obj
     * @return mixed
     */
    public static function obj2arr($obj)
    {
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (gettype($v) == 'resource') {
                return;
            }
            if (gettype($v) == 'object' || gettype($v) == 'array') {
                $obj[$k] = (array)object_to_array($v);
            }
        }

        return $obj;
    }

    /**
     * 判断键存在并且值不为空时
     * @param $key string
     * @param $array array
     * @param $elValue string
     * @return mixed
     */
    public static function existAndNotNull($key, $array, $elValue)
    {
        if (is_array($array)) {
            if (array_key_exists($key, $array)) {
                if ($array[$key] !== null) {
                    return $array[$key];
                }
            }
        }
        return $elValue;
    }

    /**
     * xml转array
     * @param $xml
     * @return mixed
     */
    public static function xml2array($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlString = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val       = json_decode(json_encode($xmlString), true);
        return $val;
    }

    /**
     * 格式化金额
     * @param mixed $number 金额
     * @param int $proNum 保留位数
     * @return string
     */
    public static function moneyFormat($number, $proNum)
    {
        if (null !== $number) {
            // 参数是不是数字类型
            if (is_numeric($number)) {
                // 以小数点分割
                $list = explode('.', $number);
                // 该数字是不是带小数
                if (count($list) >= 2) {
                    if (sizeof($list[1] >= $proNum + 1)) {
                        return number_format(mb_substr($number, 0, strlen($list[0]) + $proNum + 1), $proNum);
                    }
                }
                return number_format($number, $proNum);
            }
        }
        return number_format(0, $proNum);
    }

    public static function varDefault($value, $default = null)
    {
        if (null == $value || false == $value || '' == $value) {
            return $default;
        }
        return $value;
    }
}