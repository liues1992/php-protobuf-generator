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
 * .gary.test.GetByIdsResponse message
 */
class GetByIdsResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf <code>int32 code = 1;</code>
     */
    private $code;
    
    /**
     * Generated from protobuf <code>string msg = 2;</code>
     */
    private $msg;
    
    /**
     * data
     *
     * Generated from protobuf <code>map<int32, Info> data = 3; </code>
     */
    private $data;
    
    public function __construct()
    {
        \GPBMetadata\Tests\Test3::initOnce();
        parent::__construct();
    }
    
    /**
     * Generated from protobuf <code>int32 code = 1;</code>
     * @param integer $value
     * @return $this
     */
    public function setCode($value)
    {
        GPBUtil::checkInt32($value);
        $this->code = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>int32 code = 1;</code>
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * Generated from protobuf <code>string msg = 2;</code>
     * @param string $value
     * @return $this
     */
    public function setMsg($value)
    {
        GPBUtil::checkString($value, true);
        $this->msg = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>string msg = 2;</code>
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }
    
    /**
     * data
     *
     * Generated from protobuf <code>map<int32, Info> data = 3; </code>
     * @param \Google\Protobuf\Internal\MapField $value
     * @return $this
     */
    public function setData($value)
    {
        $value = GPBUtil::checkMapField($value, GPBType::INT32, GPBType::MESSAGE, \Gary\Test\GetByIdsResponse_Info::class);
        $this->data = $value;
        return $this;
    }
    
    /**
     * data
     *
     * Generated from protobuf <code>map<int32, Info> data = 3; </code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getData()
    {
        return $this->data;
    }
    
}
