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
 * .gary.test.GetByIdsResponse.Info message embedded in .gary.test.GetByIdsResponse message
 * info msg
 */
class GetByIdsResponse_Info extends \Google\Protobuf\Internal\Message
{
    /**
     * info id
     *
     * Generated from protobuf <code>int32 info_id = 1; </code>
     */
    private $info_id;
    
    public function __construct()
    {
        \GPBMetadata\Tests\Test3::initOnce();
        parent::__construct();
    }
    
    /**
     * info id
     *
     * Generated from protobuf <code>int32 info_id = 1; </code>
     * @param integer $value
     * @return $this
     */
    public function setInfoId($value)
    {
        GPBUtil::checkInt32($value);
        $this->info_id = $value;
        return $this;
    }
    
    /**
     * info id
     *
     * Generated from protobuf <code>int32 info_id = 1; </code>
     * @return integer
     */
    public function getInfoId()
    {
        return $this->info_id;
    }
    
}
