<?php
/**
 * Created by PhpStorm.
 * User: Gary
 * Date: 2017/12/30
 * Time: 21:00
 */

namespace Gary\Protobuf\Internal;


trait DescriptorTrait
{
    private $containing;
    private $path = [];
    private $index = 0;

    public function setIndex($i)
    {
        $this->index = $i;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function setContaining($c)
    {
        $this->containing = $c;
    }

    public function setPath(array $path)
    {
        $this->path = $path;
    }

    static $pathMap = [
        // DescriptorProto:
        // repeated FieldDescriptorProto field = 2;
        // TODO FieldDescriptorProto is also used somewhere else
        // We'll ingore extention for now
        'FieldDescriptor'     => 2,
        'Descriptor'          => [
            'Descriptor'     => 3, // repeated DescriptorProto nested_type = 3;
            'FileDescriptor' => 4, // repeated DescriptorProto message_type = 4;
        ],
        'EnumDescriptor'      => [
            'FileDescriptor' => 5, // repeated EnumDescriptorProto enum_type = 5;
            'Descriptor'     => 4, // repeated EnumDescriptorProto enum_type = 4;
        ],
        'EnumValueDescriptor' => 2, // repeated EnumValueDescriptorProto value = 2; message EnumDescriptorProto
        'OneofDescriptor'     => 8  // repeated OneofDescriptorProto oneof_decl = 8; message DescriptorProto
    ];

    /**
     * @return array
     * @throws \Exception
     */
    public function getSourceCodePath()
    {
        $path = [];
        $current = $this;
        while ($current && !$current instanceof FileDescriptor) {
            $parent = $current->getContaining();
            if (!$parent) {
                throw new \Exception("parent cannot be null");
            }
            array_unshift($path, $current->getIndex());
            // field number in definition:descriptor.proto
            $name = $this->getClassShortName($current);
            $pname = $this->getClassShortName($parent);
            if (isset(self::$pathMap[$name])) {
                if (is_int(self::$pathMap[$name])) {
                    $fieldNumber = self::$pathMap[$name];
                } else {
                    if (isset(self::$pathMap[$name][$pname])) {
                        $fieldNumber = self::$pathMap[$name][$pname];
                    } else {
                        throw new \Exception("un implemented situation $name $pname");
                    }
                }
            } else {
                throw new \Exception("un implemented situation");
            }
            array_unshift($path, $fieldNumber);
            $current = $parent;
        }
        return $path;
    }

    private function getClassShortName($obj)
    {
        $name = get_class($obj);
        $name = explode("\\", $name);
        $name = array_pop($name);
        return $name;
    }

    public function getContaining()
    {
        return $this->containing;
    }
}