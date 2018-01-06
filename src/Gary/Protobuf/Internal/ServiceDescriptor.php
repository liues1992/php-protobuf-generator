<?php
/**
 * Created by PhpStorm.
 * User: Gary
 * Date: 2018/1/6
 * Time: 14:20
 */

namespace Gary\Protobuf\Internal;


use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\MethodDescriptorProto;
use Google\Protobuf\Internal\ServiceDescriptorProto;

class ServiceDescriptor
{
    use DescriptorTrait;

    protected $methods = [];
    protected $full_name;
    protected $class;

    public static function buildFromProto(ServiceDescriptorProto $proto, $fileProto, $containing)
    {
        $desc = new static();
        $desc->setProto($proto);
        $desc->setName($proto->getName());

        $message_name_without_package  = "";
        $classname = "";
        $fullname = "";
        GPBUtil::getFullClassName(
            $proto,
            $containing,
            $fileProto,
            $message_name_without_package,
            $classname,
            $fullname);
        $desc->setName($message_name_without_package);
        $desc->setFullName($fullname);
        $desc->setClass($classname);

        /** @var MethodDescriptorProto $methodProto */
        foreach ($proto->getMethod() as $i  => $methodProto) {
            $m = MethodDescriptor::buildFromProto($methodProto);
            $m->setContaining($desc);
            $m->setIndex($i);
            $desc->addMethod($m);
        }
        return $desc;
    }

    /**
     * @return ServiceDescriptorProto
     */
    public function getProto()
    {
        return $this->proto;
    }

    public function setMethods(array $methods)
    {
        $this->methods = $methods;
    }

    public function setFullName($name)
    {
        $this->full_name = $name;
    }
    /**
     * @return MethodDescriptor[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    public function addMethod($m)
    {
        $this->methods[] = $m;
    }

    private function setClass($classname)
    {
        $this->class = $classname;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

}