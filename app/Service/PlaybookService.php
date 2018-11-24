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
     * @param string type
     * @param string $playbook
     * @return string
     * @response       []
     */
    public static function path($type, $playbook)
    {
        $suffix = config('playbook.file.suffix.' . $type);
        return base_path() . DIRECTORY_SEPARATOR . 'playbook' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR  . $playbook . $suffix;
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
}