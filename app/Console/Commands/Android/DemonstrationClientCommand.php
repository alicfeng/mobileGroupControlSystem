<?php

namespace App\Console\Commands\Android;

use App\Common\Command\AdbCmd;
use Illuminate\Console\Command;
use Log;

class DemonstrationClientCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demonstration:client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $demonstrationPath = '';
    private $device            = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->demonstrationPath = base_path() . DIRECTORY_SEPARATOR . 'playbook' . DIRECTORY_SEPARATOR . 'demonstration' . DIRECTORY_SEPARATOR . 'demonstration.samego';
        $this->device            = 'Coolpad8720L-0x6ac3d02c';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $command = 'tail -f ' . $this->demonstrationPath . ' 2>&1';
        $handler = popen($command, 'r');

        $lastTime = 0;
        while (!feof($handler)) {
            $buffer = fgets($handler);

            if (null == $buffer) continue;

            $info = explode(' ', $buffer);
            if (5 != count($info)) continue;

            list($time, $dev, $type, $code, $data) = explode(' ', $buffer);
            $time      = intval($time);//微妙
            $delayTime = $time - $lastTime;
            if (0 < $lastTime && 0 < $delayTime) {
                usleep($delayTime);
            };
            $lastTime = $time;
            $command  = str_replace('{deviceNo}', $this->device, AdbCmd::ADB_SHELL) . " sendevent {$dev} {$type} {$code} {$data}";
            system($command, $status);
            Log::info($this->device . "\t" . $command . "\t" . $status . "\n");
        }
        pclose($handler);
        return true;
    }
}
