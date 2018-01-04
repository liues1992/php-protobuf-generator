<?php

namespace Gary\Protobuf\Internal;

use Google\Protobuf\Internal\EnumDescriptorProto;
use Google\Protobuf\Internal\EnumValueDescriptorProto;
use Google\Protobuf\Internal\GPBUtil;

class EnumDescriptor
{
    use DescriptorTrait;
    
    private $klass;
    private $full_name;
    private $value;
    private $name_to_value;
    private $value_descriptor = [];
    
    public function __construct()
    {
    }


    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
    }

    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * @param     $number
     * @param  EnumValueDescriptorProto $value
     * @param int $i
     */
    public function addValue($number, $value, $i = 0)
    {
        $this->value[$number] = $value;
        $this->name_to_value[$value->getName()] = $value;
        $e = new EnumValueDescriptor($value->getName(), $number);
        $e->setIndex($i);
        $e->setContaining($this);
        $this->value_descriptor[] = $e;
    }
    
    public function values()
    {
        return $this->value_descriptor;
    }
    

    public function getValueByNumber($number)
    {
        return $this->value[$number];
    }

    public function getValueByName($name)
    {
        return $this->name_to_value[$name];
    }

    public function getValueDescriptorByIndex($index)
    {
        return $this->value_descriptor[$index];
    }

    public function getValueCount()
    {
        return count($this->value);
    }

    public function setClass($klass)
    {
        $this->klass = $klass;
    }

    public function getClass()
    {
        return $this->klass;
    }

    /**
     * @param EnumDescriptorProto $proto
     * @param $file_proto
     * @param $containing
     *
     * @return EnumDescriptor
     */
    public static function buildFromProto($proto, $file_proto, $containing)
    {
        $desc = new EnumDescriptor();

        $enum_name_without_package  = "";
        $classname = "";
        $fullname = "";
        GPBUtil::getFullClassName(
            $proto,
            $containing,
            $file_proto,
            $enum_name_without_package,
            $classname,
            $fullname);
        $desc->setFullName($fullname);
        $desc->setClass($classname);
        $values = $proto->getValue();
        foreach ($values as $i => $value) {
            $desc->addValue($value->getNumber(), $value, $i);
        }

        return $desc;
    }
}
