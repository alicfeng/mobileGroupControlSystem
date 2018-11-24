<?php
/**
 * Created by PhpStorm   User: AlicFeng   DateTime: 18-11-22 下午2:24
 */

namespace App\Service;

use App\Common\System\CliLog;
use App\Jobs\TaskQueue;


class TaskService
{
    private $playbookService;

    public function __construct()
    {
        $this->playbookService = new PlaybookService;
    }

    /**
     * @functionName   编排任务执行入口
     * @description    编排任务执行入口
     * @version        v1.0.0
     * @author         Alicfeng
     * @datetime       18-11-23 上午10:34
     * @param string $playbook 任务编排编码
     * @param string $type 类型
     * @param array $devices 设备列表
     * @param integer $frequency 操控频率
     * @return bool
     * @response       []
     */
    public function run($playbook, $type, $devices, $frequency)
    {
        CliLog::info("{$type} \ {$playbook} playbook running\n");

        foreach ($devices as $deviceItem) {
            CliLog::info("{$deviceItem} deploying  {$type} \ {$playbook} playbook\n");

            // 判断是否已经完成了 | 仅支持当天
            if ($this->playbookService->isDone($deviceItem, $playbook,$type)) {
                CliLog::info("this playbook of task had executed on this device\n");
                continue;
            };

            // 异步执行 | 提高并发
            TaskQueue::dispatch($type, $playbook, $deviceItem);
            // 频率控制 支持随机时间段
            if (strpos($frequency, ',') !== false) {
                list($minTime, $maxTime) = explode(',', $frequency);
                $frequency = rand(intval($minTime), intval($maxTime));
            }
            sleep($frequency);
        }

        CliLog::info("{$type} \ {$playbook} playbook run finished\n");
        return true;
    }
}