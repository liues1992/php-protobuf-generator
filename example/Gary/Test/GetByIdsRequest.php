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
 * .gary.test.GetByIdsRequest message
 */
class GetByIdsRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * input
     *
     * Generated from protobuf <code>repeated int32 ids = 1; </code>
     */
    private $ids;
    
    public function __construct()
    {
        \GPBMetadata\Tests\Test3::initOnce();
        parent::__construct();
    }
    
    /**
     * input
     *
     * Generated from protobuf <code>repeated int32 ids = 1; </code>
     * @param integer[]|RepeatedField $value
     * @return $this
     */
    public function setIds($value)
    {
        $value = GPBUtil::checkRepeatedField($value, GPBType::INT32);
        $this->ids = $value;
        return $this;
    }
    
    /**
     * input
     *
     * Generated from protobuf <code>repeated int32 ids = 1; </code>
     * @return integer[]|RepeatedField
     */
    public function getIds()
    {
        return $this->ids;
    }
    
}
