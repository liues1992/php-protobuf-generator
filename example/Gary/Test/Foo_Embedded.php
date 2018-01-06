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
 * .gary.test.Foo.Embedded message embedded in .gary.test.Foo message
 */
class Foo_Embedded extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf <code>int32 f1 = 1;</code>
     */
    private $f1;
    
    public function __construct()
    {
        \GPBMetadata\Tests\Test3::initOnce();
        parent::__construct();
    }
    
    /**
     * Generated from protobuf <code>int32 f1 = 1;</code>
     * @param integer $value
     * @return $this
     */
    public function setF1($value)
    {
        GPBUtil::checkInt32($value);
        $this->f1 = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>int32 f1 = 1;</code>
     * @return integer
     */
    public function getF1()
    {
        return $this->f1;
    }
    
}
