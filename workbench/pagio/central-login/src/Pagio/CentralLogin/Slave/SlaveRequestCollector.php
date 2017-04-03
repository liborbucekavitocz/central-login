<?php namespace Pagio\CentralLogin\Slave;

class SlaveRequestCollector implements SlaveRequestCollectorInterface {


    /**
     * @var SlaveRequestInterface[]
     */
    protected $items = array();
    protected $response = array();

    public function addRequest(SlaveRequestInterface $slaveRequest)
    {
        $this->items[] = $slaveRequest;
        $this->response[$slaveRequest->getKey()] = null;
    }

    /**
     * @return SlaveRequestInterface[]
     */
    public function getRequests()
    {
        return $this->items;
    }

    public function run()
    {
        $curls = array();
        foreach ($this->items as $item) {
            $curls[$item->getKey()] = curl_init();

            curl_setopt($curls[$item->getKey()], CURLOPT_URL, $item->getUrl());
            curl_setopt($curls[$item->getKey()], CURLOPT_HEADER, 0);
            curl_setopt($curls[$item->getKey()], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curls[$item->getKey()], CURLOPT_POST, ($item->getMethod() == "post"));
            curl_setopt($curls[$item->getKey()], CURLOPT_POSTFIELDS, $item->getData());
            curl_setopt($curls[$item->getKey()], CURLOPT_SSL_VERIFYPEER, false);
        }

        // create the multiple cURL handle
        $mh = curl_multi_init();

        // add the handle
        foreach ($curls as $key => $curl) {
            curl_multi_add_handle($mh, $curls[$key]);
        }

        $running = null;
        // execute the handles
        do {
            $mrc = curl_multi_exec($mh, $running);
        } while ($running);

        // close the handles
        foreach ($curls as $key => $curl) {
            curl_multi_remove_handle($mh, $curls[$key]);
        }

        curl_multi_close($mh);

        foreach ($curls as $key => $curl) {
            $response = json_decode(curl_multi_getcontent($curls[$key]));
            $this->response[$key] = !empty($response) ? (array) $response : false;
        }
        
        return $this->response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getResponseByKey($key)
    {
        if (array_key_exists($key, $this->response)) {
            return $this->response[$key];
        }

        return false;
    }
    
}