<?php
/**
 * Created by PhpStorm.
 * User: alicfeng
 * Date: 2018/11/25
 * Time: 上午11:00
 */

class AdbRecord
{
    const ADB_PATH = '/usr/local/bin/adb';

    private $device = null;

    public function __construct($device)
    {
        $this->device = $device;
    }

    public function record()
    {
        $descriptorspec = array(
            0 => STDIN,
            1 => STDOUT,
            2 => STDERR
        );

        $command = self::ADB_PATH . ' -s ' . $this->device . ' shell getevent';
        $process = proc_open($command, $descriptorspec, $pipes);

        var_dump($pipes);
        if (is_resource($process)) {
//            fwrite($pipes[0], 'content');

            while ($ret = fgets($pipes[1])) {
                echo '' . $ret;
            }
            while ($ret = fgets($pipes[2])) {
                echo '' . $ret;
            }
        }
//        pclose($process);
    }
}

$h = new AdbRecord('6L5D5P5PWS7SGAFA');
$h->record();