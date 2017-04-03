<?php namespace Pagio\CentralLogin\Slave;

interface SlaveRequestInterface {
    const POST = "post";
    const GET = "get";

    /**
     * @return mixed
     */
    public function getUrl();

    /**
     * @return array
     */
    public function getData();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return string
     */
    public function getKey();
}