<?php namespace Pagio\CentralLogin\Slave;

interface SlaveRequestCollectorInterface {
    /**
     * @param SlaveRequestInterface $slaveRequest
     *
     * @return array
     */
    public function addRequest(SlaveRequestInterface $slaveRequest);
    public function getResponse();
    public function run();
    public function getResponseByKey($key);
}