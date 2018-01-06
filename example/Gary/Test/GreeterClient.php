<?php

namespace Gary\Test;

/**
 * hello service definition
 */
class GreeterClient extends \Grpc\BaseStub
{
    
    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null)
    {
        parent::__construct($hostname, $opts, $channel);
    }
    
    /**
     * get by ids
     * @param \Gary\Test\GetByIdsRequest $request
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function GetByIds(
        \Gary\Test\GetByIdsRequest $request,
        $metadata = [], $options = [])
    {
        return $this->_simpleRequest('/gary.test.Greeter/getByIds',
            $request,
            ['\Gary\Test\GetByIdsResponse', 'decode'],
            $metadata, $options);
    }
}
