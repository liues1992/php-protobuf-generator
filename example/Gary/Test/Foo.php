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
 * .gary.test.Foo message
 * message comment??
 */
class Foo extends \Google\Protobuf\Internal\Message
{
    /**
     * field comment??
     *
     * Generated from protobuf <code>double double_field = 1; </code>
     */
    private $double_field;
    
    /**
     * Generated from protobuf <code>float float_field = 2;</code>
     */
    private $float_field;
    
    /**
     * Generated from protobuf <code>int32 int32_field = 3;</code>
     */
    private $int32_field;
    
    /**
     * Generated from protobuf <code>int64 int64_field = 4;</code>
     */
    private $int64_field;
    
    /**
     * Generated from protobuf <code>uint32 uint32_field = 5;</code>
     */
    private $uint32_field;
    
    /**
     * Generated from protobuf <code>uint64 uint64_field = 6;</code>
     */
    private $uint64_field;
    
    /**
     * Generated from protobuf <code>sint32 sint32_field = 7;</code>
     */
    private $sint32_field;
    
    /**
     * Generated from protobuf <code>sint64 sint64_field = 8;</code>
     */
    private $sint64_field;
    
    /**
     * Generated from protobuf <code>fixed32 fixed32_field = 9;</code>
     */
    private $fixed32_field;
    
    /**
     * Generated from protobuf <code>fixed64 fixed64_field = 10;</code>
     */
    private $fixed64_field;
    
    /**
     * Generated from protobuf <code>sfixed32 sfixed32_field = 11;</code>
     */
    private $sfixed32_field;
    
    /**
     * Generated from protobuf <code>sfixed64 sfixed64_field = 12;</code>
     */
    private $sfixed64_field;
    
    /**
     * Generated from protobuf <code>bool bool_field = 13;</code>
     */
    private $bool_field;
    
    /**
     * Generated from protobuf <code>string string_field = 14;</code>
     */
    private $string_field;
    
    /**
     * Generated from protobuf <code>bytes bytes_field = 15;</code>
     */
    private $bytes_field;
    
    /**
     * Generated from protobuf <code>repeated int32 repeated_field = 16;</code>
     */
    private $repeated_field;
    
    /**
     * Generated from protobuf <code>Bar embedded_field = 17;</code>
     */
    private $embedded_field;
    
    /**
     * Generated from protobuf <code>repeated double double_packed_field = 18 [packed=true];</code>
     */
    private $double_packed_field;
    
    /**
     * Generated from protobuf <code>repeated float float_packed_field = 19 [packed=true];</code>
     */
    private $float_packed_field;
    
    /**
     * Generated from protobuf <code>repeated fixed32 fixed32_packed_field = 20 [packed=true];</code>
     */
    private $fixed32_packed_field;
    
    /**
     * Generated from protobuf <code>repeated fixed64 fixed64_packed_field = 21 [packed=true];</code>
     */
    private $fixed64_packed_field;
    
    /**
     * Generated from protobuf <code>repeated int32 int32_packed_field = 22 [packed=true];</code>
     */
    private $int32_packed_field;
    
    /**
     * Generated from protobuf <code>repeated sint32 sint32_packed_field = 23 [packed=true];</code>
     */
    private $sint32_packed_field;
    
    /**
     * Generated from protobuf <code>repeated bool bool_packed_field = 24 [packed=true];</code>
     */
    private $bool_packed_field;
    
    /**
     * Generated from protobuf <code>Baz optional_embedded_field = 25;</code>
     */
    private $optional_embedded_field;
    
    /**
     * Generated from protobuf <code>repeated Bar repeated_obj_field = 26;</code>
     */
    private $repeated_obj_field;
    
    /**
     * Generated from protobuf <code>repeated string repeated_string_field = 27;</code>
     */
    private $repeated_string_field;
    
    /**
     * Generated from protobuf <code>map<int32, Embedded> map_field = 28;</code>
     */
    private $map_field;
    
    public function __construct()
    {
        \GPBMetadata\Tests\Test3::initOnce();
        parent::__construct();
    }
    
    /**
     * field comment??
     *
     * Generated from protobuf <code>double double_field = 1; </code>
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
     * field comment??
     *
     * Generated from protobuf <code>double double_field = 1; </code>
     * @return double
     */
    public function getDoubleField()
    {
        return $this->double_field;
    }
    
    /**
     * Generated from protobuf <code>float float_field = 2;</code>
     * @param double $value
     * @return $this
     */
    public function setFloatField($value)
    {
        GPBUtil::checkFloat($value);
        $this->float_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>float float_field = 2;</code>
     * @return double
     */
    public function getFloatField()
    {
        return $this->float_field;
    }
    
    /**
     * Generated from protobuf <code>int32 int32_field = 3;</code>
     * @param integer $value
     * @return $this
     */
    public function setInt32Field($value)
    {
        GPBUtil::checkInt32($value);
        $this->int32_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>int32 int32_field = 3;</code>
     * @return integer
     */
    public function getInt32Field()
    {
        return $this->int32_field;
    }
    
    /**
     * Generated from protobuf <code>int64 int64_field = 4;</code>
     * @param integer $value
     * @return $this
     */
    public function setInt64Field($value)
    {
        GPBUtil::checkInt64($value);
        $this->int64_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>int64 int64_field = 4;</code>
     * @return integer
     */
    public function getInt64Field()
    {
        return $this->int64_field;
    }
    
    /**
     * Generated from protobuf <code>uint32 uint32_field = 5;</code>
     * @param integer $value
     * @return $this
     */
    public function setUint32Field($value)
    {
        GPBUtil::checkUInt32($value);
        $this->uint32_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>uint32 uint32_field = 5;</code>
     * @return integer
     */
    public function getUint32Field()
    {
        return $this->uint32_field;
    }
    
    /**
     * Generated from protobuf <code>uint64 uint64_field = 6;</code>
     * @param integer $value
     * @return $this
     */
    public function setUint64Field($value)
    {
        GPBUtil::checkUInt64($value);
        $this->uint64_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>uint64 uint64_field = 6;</code>
     * @return integer
     */
    public function getUint64Field()
    {
        return $this->uint64_field;
    }
    
    /**
     * Generated from protobuf <code>sint32 sint32_field = 7;</code>
     * @param integer $value
     * @return $this
     */
    public function setSint32Field($value)
    {
        GPBUtil::checkInt32($value);
        $this->sint32_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>sint32 sint32_field = 7;</code>
     * @return integer
     */
    public function getSint32Field()
    {
        return $this->sint32_field;
    }
    
    /**
     * Generated from protobuf <code>sint64 sint64_field = 8;</code>
     * @param integer $value
     * @return $this
     */
    public function setSint64Field($value)
    {
        GPBUtil::checkInt64($value);
        $this->sint64_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>sint64 sint64_field = 8;</code>
     * @return integer
     */
    public function getSint64Field()
    {
        return $this->sint64_field;
    }
    
    /**
     * Generated from protobuf <code>fixed32 fixed32_field = 9;</code>
     * @param integer $value
     * @return $this
     */
    public function setFixed32Field($value)
    {
        GPBUtil::checkUInt32($value);
        $this->fixed32_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>fixed32 fixed32_field = 9;</code>
     * @return integer
     */
    public function getFixed32Field()
    {
        return $this->fixed32_field;
    }
    
    /**
     * Generated from protobuf <code>fixed64 fixed64_field = 10;</code>
     * @param integer $value
     * @return $this
     */
    public function setFixed64Field($value)
    {
        GPBUtil::checkUInt64($value);
        $this->fixed64_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>fixed64 fixed64_field = 10;</code>
     * @return integer
     */
    public function getFixed64Field()
    {
        return $this->fixed64_field;
    }
    
    /**
     * Generated from protobuf <code>sfixed32 sfixed32_field = 11;</code>
     * @param integer $value
     * @return $this
     */
    public function setSfixed32Field($value)
    {
        GPBUtil::checkInt32($value);
        $this->sfixed32_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>sfixed32 sfixed32_field = 11;</code>
     * @return integer
     */
    public function getSfixed32Field()
    {
        return $this->sfixed32_field;
    }
    
    /**
     * Generated from protobuf <code>sfixed64 sfixed64_field = 12;</code>
     * @param integer $value
     * @return $this
     */
    public function setSfixed64Field($value)
    {
        GPBUtil::checkInt64($value);
        $this->sfixed64_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>sfixed64 sfixed64_field = 12;</code>
     * @return integer
     */
    public function getSfixed64Field()
    {
        return $this->sfixed64_field;
    }
    
    /**
     * Generated from protobuf <code>bool bool_field = 13;</code>
     * @param boolean $value
     * @return $this
     */
    public function setBoolField($value)
    {
        GPBUtil::checkBool($value);
        $this->bool_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>bool bool_field = 13;</code>
     * @return boolean
     */
    public function getBoolField()
    {
        return $this->bool_field;
    }
    
    /**
     * Generated from protobuf <code>string string_field = 14;</code>
     * @param string $value
     * @return $this
     */
    public function setStringField($value)
    {
        GPBUtil::checkString($value, true);
        $this->string_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>string string_field = 14;</code>
     * @return string
     */
    public function getStringField()
    {
        return $this->string_field;
    }
    
    /**
     * Generated from protobuf <code>bytes bytes_field = 15;</code>
     * @param string $value
     * @return $this
     */
    public function setBytesField($value)
    {
        GPBUtil::checkString($value, false);
        $this->bytes_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>bytes bytes_field = 15;</code>
     * @return string
     */
    public function getBytesField()
    {
        return $this->bytes_field;
    }
    
    /**
     * Generated from protobuf <code>repeated int32 repeated_field = 16;</code>
     * @param integer[]|RepeatedField $value
     * @return $this
     */
    public function setRepeatedField($value)
    {
        $value = GPBUtil::checkRepeatedField($value, GPBType::INT32);
        $this->repeated_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>repeated int32 repeated_field = 16;</code>
     * @return integer[]|RepeatedField
     */
    public function getRepeatedField()
    {
        return $this->repeated_field;
    }
    
    /**
     * Generated from protobuf <code>Bar embedded_field = 17;</code>
     * @param \Gary\Test\Bar $value
     * @return $this
     */
    public function setEmbeddedField($value)
    {
        GPBUtil::checkMessage($value, \Gary\Test\Bar::class);
        $this->embedded_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>Bar embedded_field = 17;</code>
     * @return \Gary\Test\Bar
     */
    public function getEmbeddedField()
    {
        return $this->embedded_field;
    }
    
    /**
     * Generated from protobuf <code>repeated double double_packed_field = 18 [packed=true];</code>
     * @param double[]|RepeatedField $value
     * @return $this
     */
    public function setDoublePackedField($value)
    {
        $value = GPBUtil::checkRepeatedField($value, GPBType::DOUBLE);
        $this->double_packed_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>repeated double double_packed_field = 18 [packed=true];</code>
     * @return double[]|RepeatedField
     */
    public function getDoublePackedField()
    {
        return $this->double_packed_field;
    }
    
    /**
     * Generated from protobuf <code>repeated float float_packed_field = 19 [packed=true];</code>
     * @param double[]|RepeatedField $value
     * @return $this
     */
    public function setFloatPackedField($value)
    {
        $value = GPBUtil::checkRepeatedField($value, GPBType::FLOAT);
        $this->float_packed_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>repeated float float_packed_field = 19 [packed=true];</code>
     * @return double[]|RepeatedField
     */
    public function getFloatPackedField()
    {
        return $this->float_packed_field;
    }
    
    /**
     * Generated from protobuf <code>repeated fixed32 fixed32_packed_field = 20 [packed=true];</code>
     * @param integer[]|RepeatedField $value
     * @return $this
     */
    public function setFixed32PackedField($value)
    {
        $value = GPBUtil::checkRepeatedField($value, GPBType::FIXED32);
        $this->fixed32_packed_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>repeated fixed32 fixed32_packed_field = 20 [packed=true];</code>
     * @return integer[]|RepeatedField
     */
    public function getFixed32PackedField()
    {
        return $this->fixed32_packed_field;
    }
    
    /**
     * Generated from protobuf <code>repeated fixed64 fixed64_packed_field = 21 [packed=true];</code>
     * @param integer[]|RepeatedField $value
     * @return $this
     */
    public function setFixed64PackedField($value)
    {
        $value = GPBUtil::checkRepeatedField($value, GPBType::FIXED64);
        $this->fixed64_packed_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>repeated fixed64 fixed64_packed_field = 21 [packed=true];</code>
     * @return integer[]|RepeatedField
     */
    public function getFixed64PackedField()
    {
        return $this->fixed64_packed_field;
    }
    
    /**
     * Generated from protobuf <code>repeated int32 int32_packed_field = 22 [packed=true];</code>
     * @param integer[]|RepeatedField $value
     * @return $this
     */
    public function setInt32PackedField($value)
    {
        $value = GPBUtil::checkRepeatedField($value, GPBType::INT32);
        $this->int32_packed_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>repeated int32 int32_packed_field = 22 [packed=true];</code>
     * @return integer[]|RepeatedField
     */
    public function getInt32PackedField()
    {
        return $this->int32_packed_field;
    }
    
    /**
     * Generated from protobuf <code>repeated sint32 sint32_packed_field = 23 [packed=true];</code>
     * @param integer[]|RepeatedField $value
     * @return $this
     */
    public function setSint32PackedField($value)
    {
        $value = GPBUtil::checkRepeatedField($value, GPBType::SINT32);
        $this->sint32_packed_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>repeated sint32 sint32_packed_field = 23 [packed=true];</code>
     * @return integer[]|RepeatedField
     */
    public function getSint32PackedField()
    {
        return $this->sint32_packed_field;
    }
    
    /**
     * Generated from protobuf <code>repeated bool bool_packed_field = 24 [packed=true];</code>
     * @param boolean[]|RepeatedField $value
     * @return $this
     */
    public function setBoolPackedField($value)
    {
        $value = GPBUtil::checkRepeatedField($value, GPBType::BOOL);
        $this->bool_packed_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>repeated bool bool_packed_field = 24 [packed=true];</code>
     * @return boolean[]|RepeatedField
     */
    public function getBoolPackedField()
    {
        return $this->bool_packed_field;
    }
    
    /**
     * Generated from protobuf <code>Baz optional_embedded_field = 25;</code>
     * @param \Gary\Test\Baz $value
     * @return $this
     */
    public function setOptionalEmbeddedField($value)
    {
        GPBUtil::checkMessage($value, \Gary\Test\Baz::class);
        $this->optional_embedded_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>Baz optional_embedded_field = 25;</code>
     * @return \Gary\Test\Baz
     */
    public function getOptionalEmbeddedField()
    {
        return $this->optional_embedded_field;
    }
    
    /**
     * Generated from protobuf <code>repeated Bar repeated_obj_field = 26;</code>
     * @param \Gary\Test\Bar[]|RepeatedField $value
     * @return $this
     */
    public function setRepeatedObjField($value)
    {
        $value = GPBUtil::checkRepeatedField($value, GPBType::MESSAGE, \Gary\Test\Bar::class);
        $this->repeated_obj_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>repeated Bar repeated_obj_field = 26;</code>
     * @return \Gary\Test\Bar[]|RepeatedField
     */
    public function getRepeatedObjField()
    {
        return $this->repeated_obj_field;
    }
    
    /**
     * Generated from protobuf <code>repeated string repeated_string_field = 27;</code>
     * @param string[]|RepeatedField $value
     * @return $this
     */
    public function setRepeatedStringField($value)
    {
        $value = GPBUtil::checkRepeatedField($value, GPBType::STRING);
        $this->repeated_string_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>repeated string repeated_string_field = 27;</code>
     * @return string[]|RepeatedField
     */
    public function getRepeatedStringField()
    {
        return $this->repeated_string_field;
    }
    
    /**
     * Generated from protobuf <code>map<int32, Embedded> map_field = 28;</code>
     * @param \Google\Protobuf\Internal\MapField $value
     * @return $this
     */
    public function setMapField($value)
    {
        $value = GPBUtil::checkMapField($value, GPBType::INT32, GPBType::MESSAGE, \Gary\Test\Foo_Embedded::class);
        $this->map_field = $value;
        return $this;
    }
    
    /**
     * Generated from protobuf <code>map<int32, Embedded> map_field = 28;</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getMapField()
    {
        return $this->map_field;
    }
    
}
