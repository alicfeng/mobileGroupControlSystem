<?php
/**
 * Created by PhpStorm   User: AlicFeng   DateTime: 18-11-23 上午11:38
 */

namespace App\Service;


use App\Common\Database\Redis\SamegoRedis;

class PlaybookService
{
    private $redis;

    public function __construct()
    {
        $this->redis = SamegoRedis::getRedis();
    }

    /**
     * @functionName   根据编排名称获取绝对路径
     * @description    根据编排名称获取绝对路径
     * @version        v1.0.0
     * @author         Alicfeng
     * @datetime       18-11-23 下午6:44
     * @param string type
     * @param string $playbook
     * @return string
     * @response       []
     */
    public static function path($type, $playbook)
    {
        $suffix = config('playbook.file.suffix.' . $type);
        return base_path() . DIRECTORY_SEPARATOR . 'playbook' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $playbook . $suffix;
    }

    /**
     * @functionName   检查编排任务是否正常
     * @description    编排任务文件是否存在、编排内容是否正常
     * @version        v1.0.0
     * @author         Alicfeng
     * @datetime       18-11-23 下午6:42
     * @param string $type
     * @param string $playbook
     * @return bool
     * @response       []
     */
    public function check($type, $playbook)
    {
        $playbookFile = self::path($type, $playbook);
        return file_exists($playbookFile);
    }

    /**
     * @functionName   判断任务是否已经做了
     * @description    判断任务今天做了吗
     * @version        v1.0.0
     * @author         Alicfeng
     * @datetime       18-11-24 下午1:01
     * @param $device
     * @param $playbook
     * @param $type
     * @return bool
     * @response       []
     */
    public function isDone($device, $playbook, $type)
    {
        $isDone = $this->redis->get($device . $playbook . $type);
        return false == $isDone ? false : true;
    }

    /**
     * @functionName   设置当前任务今天已做
     * @description    设置当前任务今天已做
     * @version        v1.0.0
     * @author         Alicfeng
     * @datetime       18-11-24 下午1:01
     * @param $device
     * @param $playbook
     * @param $type
     * @return bool
     * @response       []
     */
    public function setDone($device, $playbook, $type)
    {
        // 今天还有多少时间(s)
        $time = strtotime(date('Y-m-d', strtotime('+1 day'))) - time();
        return $this->redis->set($device . $playbook . $type, 1, $time);
    }
}