<?php
/**
 * Auto generated from tests/test3.proto at 2018-01-06 10:36:20
 *
 * package: gary.test
 */

namespace Gary\Test;
use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * .gary.test.Bar message
 */
class Bar extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf <code>double double_field = 1;</code>
     */
    private $double_field;
    
    public function __construct()
    {
        \GPBMetadata\Tests\Test3::initOnce();
        parent::__construct();
    }
    
    /**
     * Generated from protobuf <code>double double_field = 1;</code>
     * @param double $value
     * @return $this
     */
    public function setDoubleField($value)
    {
        GPBUtil::checkDouble($value);
        $this->double_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>double double_field = 1;</code>
     * @return double
     */
    public function getDoubleField()
    {
        return $this->double_field;
    }
    
}
