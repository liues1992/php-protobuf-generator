<?php
# Generated by the php-protobuf-generator.  DO NOT EDIT!
# source: tests/test3.proto

namespace Gary\Test;
use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * .gary.test.Baz message
 */
class Baz extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf <code>int32 id = 1;</code>
     */
    private $id;
    
    public function __construct()
    {
        \GPBMetadata\Tests\Test3::initOnce();
        parent::__construct();
    }
    
    /**
     * Generated from protobuf <code>int32 id = 1;</code>
     * @param integer $value
     * @return $this
     */
    public function setId($value)
    {
        GPBUtil::checkInt32($value);
        $this->id = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>int32 id = 1;</code>
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
}
