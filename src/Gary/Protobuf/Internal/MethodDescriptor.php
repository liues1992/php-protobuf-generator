<?php
/**
 * Created by PhpStorm.
 * User: Gary
 * Date: 2018/1/6
 * Time: 14:20
 */

namespace Gary\Protobuf\Internal;


use Google\Protobuf\Internal\MethodDescriptorProto;
use Google\Protobuf\Internal\ServiceDescriptorProto;

class MethodDescriptor
{
    use DescriptorTrait;

    private $input_type;
    private $output_type;

    public static function buildFromProto(MethodDescriptorProto $proto)
    {
        $desc = new static();
        $desc->setProto($proto);
        $desc->setName($proto->getName());

        // link this later
        $desc->setInputType($proto->getInputType());
        $desc->setOutputType($proto->getOutputType());
        return $desc;
    }

    /**
     * @return MethodDescriptorProto
     */
    public function getProto()
    {
        return $this->proto;
    }

    /**
     * @return Descriptor|string
     */
    public function getInputType()
    {
        return $this->input_type;
    }

    /**
     * @param mixed $input_type
     */
    public function setInputType($input_type)
    {
        $this->input_type = $input_type;
    }

    /**
     * @return mixed
     */
    public function getOutputType()
    {
        return $this->output_type;
    }

    /**
     * @param mixed $output_type
     */
    public function setOutputType($output_type)
    {
        $this->output_type = $output_type;
    }

}