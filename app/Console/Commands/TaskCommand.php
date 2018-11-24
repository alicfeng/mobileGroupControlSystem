<?php

namespace App\Console\Commands;

use App\Common\Constant\Strings;
use App\Common\Constant\TypeTask;
use App\Common\System\CliLog;
use App\Service\DeviceService;
use App\Service\PlaybookService;
use App\Service\TaskService;
use Illuminate\Console\Command;
use Log;

class TaskCommand extends Command
{
    protected $signature
                           = 'task:do 
                           {help?} 
                           {--devices=true}
                           {--playbook=} 
                           {--type=} 
                           {--amount=} 
                           {--frequency=}';
    protected $description = 'playbook task run';

    private $deviceService;
    private $playbookService;
    private $taskService;
    private $deviceList;

    private $help      = null;// 帮助
    private $playbook  = null;// 任务编码 | must | string
    private $type      = null;// 类型 | must | string | {script:default | playbook}
    private $devices   = true;// 设备
    private $amount    = 0;// 任务数量 | must | int
    private $frequency = 0;// 频率 | optional | 单位s | 默认0s

    /**
     * TaskCommand constructor.
     * @param DeviceService $deviceService
     * @param TaskService $taskService
     * @param PlaybookService $playbookService
     */
    public function __construct(DeviceService $deviceService, TaskService $taskService, PlaybookService $playbookService)
    {
        parent::__construct();
        $this->deviceService   = $deviceService;
        $this->taskService     = $taskService;
        $this->playbookService = $playbookService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->init();
        $this->taskMessage();
        $this->checkTaskAmount();
        $this->checkPlaybook();

        $this->taskService->run($this->playbook, $this->type, $this->deviceList, $this->frequency);
        return true;
    }

    /**
     * command for help
     */
    public function help()
    {
        exit("usage:\ntask:do 
        help
        --devices  view devices main info
        --playbook=playbook code
        --type=playbook type
        --amount=task amount
        --frequency=execute task frequency | s\n");
    }

    /**
     * command for init
     */
    private function init()
    {
        $this->playbook = $this->option('playbook');
        $this->type     = $this->option('type');
        $this->amount   = $this->option('amount');
        $frequency      = $this->option('frequency');
        $frequency ? $this->frequency = $frequency : null;

        $this->devices = $this->option('devices');
        $this->help    = $this->argument('help');

        $this->deviceList = $this->deviceService->deviceNoList();

        if ('help' == $this->help) {
            $this->help();
        }
        if (null == $this->devices) {
            $this->devicesInfo();
        }

        // check任务编码
        if (Strings::NULL == $this->playbook || null == $this->playbook) {
            echo "playbook can not be empty\n";
            $this->help();
        }

        // check任务数量
        if (Strings::NULL == $this->amount || null == $this->amount || !is_numeric($this->amount)) {
            echo "amount can not be empty\n";
            $this->help();
        }

        // check type
        if (null == $this->type) {
            $this->type = TypeTask::SCRIPT;
        } else {
            if (!in_array($this->type, [TypeTask::SCRIPT, TypeTask::PLAYBOOK])) {
                CliLog::info('please check type value~ script:default | playbook');
            }
        }
    }

    /**
     * @functionName   任务编排信息
     * @description    description
     * @version        v1.0.0
     * @author         Alicfeng
     * @datetime       18-11-23 上午9:52
     * @response       []
     */
    private function taskMessage()
    {
        CliLog::info("Task main message :\n");
        CliLog::info("playbook\t{$this->playbook}\n");
        CliLog::info("amount\t\t{$this->amount}\n");
        CliLog::info("frequency\t{$this->frequency}\n");
    }

    /**
     * @functionName   设备概览信息
     * @description    设备概览信息
     * @version        v1.0.0
     * @author         Alicfeng
     * @datetime       18-11-23 上午9:54
     * @response       []
     */
    private function devicesInfo()
    {
        if (null == $this->devices) {
            $devices = $this->deviceService->devicesInfo($this->deviceList);
            if (false != $devices) {
                foreach ($devices as $deviceItem) {
                    echo "{$deviceItem['deviceNo']}\t {$deviceItem['product']}\n";
                    Log::info("{$deviceItem['deviceNo']}\t {$deviceItem['product']}\n");
                }
            }
            exit(0);
        }
    }

    /**
     * @functionName   check任务的量是否可行
     * @description    检验任务的数量是否小于设备
     * @version        v1.0.0
     * @author         Alicfeng
     * @datetime       18-11-23 上午9:52
     * @response       []
     */
    private function checkTaskAmount()
    {
        if ($this->amount > count($this->deviceList)) {
            Log::warning('warning ~ the task amount greater than device amount');
            exit("warning ~ the task amount greater than device amount\n");
        }
    }

    /**
     * @functionName   checkPlaybook
     * @description    checkPlaybook
     * @version        v1.0.0
     * @author         Alicfeng
     * @datetime       18-11-23 下午6:50
     * @response       []
     */
    private function checkPlaybook()
    {
        if (false == $this->playbookService->check($this->type,$this->playbook)) {
            Log::warning('the task of playbook not exist');
            exit("the task of playbook not exist\n");
        }
    }
}
