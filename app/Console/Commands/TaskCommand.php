<?php

namespace App\Console\Commands;

use App\Common\Constant\Strings;
use Illuminate\Console\Command;

class TaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:do {help?} {--taskCode=} {--amount=} {--frequency}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    private $help      = null;// 帮助
    private $taskCode  = null;// 任务编码
    private $amount    = 0;// 任务数量
    private $frequency = 0;// 频率 | 单位s | 默认0s

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->init();

        return true;
    }

    /**
     * command for help
     */
    public function help()
    {
        exit("usage:\ntask:do {help?} {--taskCode=} {--amount=} {--frequency}\n");
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

        $this->help = $this->argument('help');

        if ('help' == $this->help) {
            $this->help();
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
        $this->taskMessage();
    }

    private function taskMessage()
    {
        echo "Task main message :\n";
        echo "taskCode\t{$this->taskCode}\n";
        echo "amount\t\t{$this->amount}\n";
        echo "frequency\t{$this->frequency}\n";
    }
}
