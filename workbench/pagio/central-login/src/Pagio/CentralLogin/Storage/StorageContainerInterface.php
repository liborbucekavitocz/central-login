<?php namespace Pagio\CentralLogin\Storage;

interface StorageContainerInterface {
    public function forget();

    public function forgetToken1($key);

    public function setToken1($key, $token1);

    public function getToken1($key);
}