<?php

namespace App\Jobs;

use App\Common\Command\AdbCmd;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;

class TaskQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // 重试3次
    protected $tries = 3;

    private $taskList;
    private $device;

    public function __construct($taskList, $device)
    {
        $this->taskList = $taskList;
        $this->device   = $device;
    }


    public function handle()
    {
        foreach ($this->taskList as $task) {
            $command = AdbCmd::BASIC_ADB . " -s {$this->device} shell " . $task;
            exec($command, $result, $status);
            Log::info($this->device . "\t" . $command . "\t" . json_encode($result, JSON_UNESCAPED_UNICODE));

            sleep(3);
            // 指令执行失败 队列失败重试
            if (0 != $status) {
                $this->fail();
            }
        }
    }
}
