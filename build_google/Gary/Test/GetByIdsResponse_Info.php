<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: tests/test3.proto

namespace Gary\Test;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * info msg
 *
 * Generated from protobuf message <code>gary.test.GetByIdsResponse.Info</code>
 */
class GetByIdsResponse_Info extends \Google\Protobuf\Internal\Message
{
    /**
     * info id
     *
     * Generated from protobuf field <code>int32 info_id = 1;</code>
     */
    private $info_id = 0;

    public function __construct() {
        \GPBMetadata\Tests\Test3::initOnce();
        parent::__construct();
    }

    /**
     * info id
     *
     * Generated from protobuf field <code>int32 info_id = 1;</code>
     * @return int
     */
    public function getInfoId()
    {
        return $this->info_id;
    }

    /**
     * info id
     *
     * Generated from protobuf field <code>int32 info_id = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setInfoId($var)
    {
        GPBUtil::checkInt32($var);
        $this->info_id = $var;

        return $this;
    }

}

