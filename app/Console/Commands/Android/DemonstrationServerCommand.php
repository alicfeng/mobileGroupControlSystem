<?php

namespace App\Console\Commands\Android;

use App\Utils\DateUtil;
use Illuminate\Console\Command;

class DemonstrationServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demonstration:server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $demonstrationPath = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->demonstrationPath = base_path() . DIRECTORY_SEPARATOR . 'playbook' . DIRECTORY_SEPARATOR . 'demonstration' . DIRECTORY_SEPARATOR . 'demonstration.samego';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (file_exists($this->demonstrationPath)) {
            unlink($this->demonstrationPath);
        }
        $demonstrationFile = fopen($this->demonstrationPath, "w") or die("Unable to open file!");


        $command = 'adb -s Coolpad8720L-0x87b0c281 shell getevent 2>&1';
        $handler = popen($command, 'r');

        while (!feof($handler)) {
            $buffer = fgets($handler);

            $info = explode(' ', $buffer);
            if (4 != count($info)) continue;

            $pattern = "/^\/dev\/input\/event(.)*?$/";

            if (preg_match($pattern, $buffer)) {

                list($dev, $type, $code, $data) = $info;

                $infoLine = DateUtil::microTime() . ' ' . str_replace(':', '', $dev) . ' ' . hexdec($type) . ' ' . hexdec($code) . ' ' . hexdec($data) . "\n";
                fwrite($demonstrationFile, $infoLine);
                flush();
            }
        }
        pclose($handler);
        fclose($demonstrationFile);
        return true;
    }
}
