<?php namespace Pagio\CentralLogin\Storage;

class StorageContainer implements StorageContainerInterface {

    protected $storageRepository;

    public function __construct(StorageRepositoryInterface $storageRepository)
    {
        $this->storageRepository = $storageRepository;
    }

    public function forget()
    {
        return $this->storageRepository->forget();
    }

    public function forgetToken1($key)
    {
        return $this->storageRepository->forgetToken1($key);
    }

    public function setToken1($key, $token1)
    {
        return $this->storageRepository->setToken1($key, $token1);
    }

    public function getToken1($key)
    {
        return $this->storageRepository->getToken1($key);
    }

}