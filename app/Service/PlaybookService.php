<?php
/**
 * Created by PhpStorm   User: AlicFeng   DateTime: 18-11-23 上午11:38
 */

namespace App\Service;


class PlaybookService
{
    /**
     * @functionName   根据编排名称获取绝对路径
     * @description    根据编排名称获取绝对路径
     * @version        v1.0.0
     * @author         Alicfeng
     * @datetime       18-11-23 下午6:44
     * @param $playbook
     * @return string
     * @response       []
     */
    public static function path($playbook)
    {
        return base_path() . '/playbook/' . $playbook . '.pb';
    }

    /**
     * @functionName   检查编排任务是否正常
     * @description    编排任务文件是否存在、编排内容是否正常
     * @version        v1.0.0
     * @author         Alicfeng
     * @datetime       18-11-23 下午6:42
     * @param $playbook
     * @return bool
     * @response       []
     */
    public function check($playbook)
    {
        $playbookFile = self::path($playbook);
        return file_exists($playbookFile);
    }
}