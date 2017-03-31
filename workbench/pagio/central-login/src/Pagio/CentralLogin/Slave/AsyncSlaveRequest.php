<?php namespace Pagio\CentralLogin\Slave;

class AsyncSlaveRequest extends \Thread {

    public function run()
    {
        var_dump($this->getThreadId()); exit;
    }

}