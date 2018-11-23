<?php

namespace App\Console\Commands;

use App\Common\Constant\Strings;
use App\Service\DeviceService;
use App\Service\TaskService;
use Illuminate\Console\Command;
use Log;

class TaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:do {help?} {--devices=true} {--taskCode=} {--amount=} {--frequency}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $deviceService;
    private $taskService;
    private $deviceList;

    private $help      = null;// 帮助
    private $taskCode  = null;// 任务编码
    private $devices   = true;// 设备
    private $amount    = 0;// 任务数量
    private $frequency = 0;// 频率 | 单位s | 默认0s

    /**
     * TaskCommand constructor.
     * @param DeviceService $deviceService
     * @param TaskService $taskService
     */
    public function __construct(DeviceService $deviceService, TaskService $taskService)
    {
        parent::__construct();
        $this->deviceService = $deviceService;
        $this->taskService   = $taskService;
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
        $this->taskService->run($this->taskCode, $this->deviceList, $this->frequency);
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
        --taskCode=playbook code
        --amount=task amount
        --frequency=execute task frequency | s\n");
    }

    /**
     * command for init
     */
    private function init()
    {
        $this->taskCode = $this->option('taskCode');
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
        if (Strings::NULL == $this->taskCode || null == $this->taskCode) {
            echo "taskCode can not be empty\n";
            $this->help();
        }

        // check任务数量
        if (Strings::NULL == $this->amount || null == $this->amount || !is_numeric($this->amount)) {
            echo "amount can not be empty\n";
            $this->help();
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
        echo "Task main message :\n";
        echo "taskCode\t{$this->taskCode}\n";
        echo "amount\t\t{$this->amount}\n";
        echo "frequency\t{$this->frequency}\n";
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
}
