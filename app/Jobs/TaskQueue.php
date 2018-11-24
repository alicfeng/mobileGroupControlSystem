<?php

namespace App\Jobs;

use App\Common\Command\AdbCmd;
use App\Common\Command\PyCmd;
use App\Common\Constant\TypeTask;
use App\Common\System\CliLog;
use App\Service\PlaybookService;
use App\Utils\ObjectUtil;
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

    private $playbook;
    private $type;
    private $playbookPath;
    private $device;

    public function __construct($type, $playbook, $device)
    {
        $this->type         = $type;
        $this->playbook     = $playbook;
        $this->playbookPath = PlaybookService::path($type, $playbook);
        $this->device       = $device;
    }


    public function handle()
    {
        switch ($this->type) {
            case TypeTask::SCRIPT:
                $this->scriptHandle();
                break;
            case TypeTask::PLAYBOOK:
                $this->playbookHandle();
                break;
            default:
                break;
        }
    }

    private function scriptHandle()
    {
        $command = PyCmd::adbRecordPlay($this->playbookPath);

        exec($command, $result, $status);
        Log::info($this->device . "\t" . $command . "\t" . json_encode($result, JSON_UNESCAPED_UNICODE));
        // 指令执行失败 队列失败重试
        if (0 != $status) {
            $this->fail();
        }
    }

    private function playbookHandle()
    {
        $file = fopen($this->playbookPath, "r") or die("Unable to open file!");
        $content = fread($file, filesize($this->playbookPath));
        fclose($file);
        $executeStepList = ObjectUtil::jsonDecode($content);
        if (false == $executeStepList) {
            return false;
        }
        foreach ($executeStepList as $stepItem) {
            $command = str_replace('{deviceNo}', $this->device, AdbCmd::ADB_SHELL) . ' ' . $stepItem->command;
            exec($command, $result, $status);
            CliLog::info($this->device . "\t" . $command . "\t" . json_encode($result, JSON_UNESCAPED_UNICODE)."\n");
            // 指令执行失败 队列失败重试
            if (0 != $status) {
                $this->fail();
            }
            sleep($stepItem->time);
        }
        return true;
    }
}
