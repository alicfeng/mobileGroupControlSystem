<?php

namespace App\Jobs;

use App\Common\Command\AdbCmd;
use App\Common\Command\PyCmd;
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

    private $playbookFile;
    private $device;

    public function __construct($playbookFile, $device)
    {
        $this->playbookFile = $playbookFile;
        $this->device       = $device;
    }


    public function handle()
    {
        $command = PyCmd::adbRecordPlay($this->playbookFile);
        exec($command, $result, $status);
        Log::info($this->device . "\t" . $command . "\t" . json_encode($result, JSON_UNESCAPED_UNICODE));
        // 指令执行失败 队列失败重试
        if (0 != $status) {
            $this->fail();
        }
    }
}
