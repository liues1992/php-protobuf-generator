<?php

// Protocol Buffers - Google's data interchange format
// Copyright 2008 Google Inc.  All rights reserved.
// https://developers.google.com/protocol-buffers/
//
// Redistribution and use in source and binary forms, with or without
// modification, are permitted provided that the following conditions are
// met:
//
//     * Redistributions of source code must retain the above copyright
// notice, this list of conditions and the following disclaimer.
//     * Redistributions in binary form must reproduce the above
// copyright notice, this list of conditions and the following disclaimer
// in the documentation and/or other materials provided with the
// distribution.
//     * Neither the name of Google Inc. nor the names of its
// contributors may be used to endorse or promote products derived from
// this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
// A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
// OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
// LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
// DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
// THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
// (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
// OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

namespace Gary\Protobuf\Internal;

use Google\Protobuf\Internal\FieldDescriptorProto;
use Google\Protobuf\Internal\GPBLabel;
use Google\Protobuf\Internal\GPBType;

class FieldDescriptor
{
    use DescriptorTrait;

    private $json_name;
    private $setter;
    private $getter;
    private $number;
    private $label;
    private $type;
    private $message_type;
    private $enum_type;
    private $packed;
    private $is_map;
    private $oneof_index = -1;
    private $typeName = '';

    /**
     * Returns scalar type
     *
     * @return int
     */
    public function getSimpleTypeName()
    {
        return self::$_typeSimpleName[$this->getType()];
    }

    private static $_protoTypeName = array(
        GPBType::DOUBLE   => 'double',
        GPBType::FLOAT    => 'float',
        GPBType::INT64    => 'int64',
        GPBType::UINT64   => 'uint64',
        GPBType::INT32    => 'int32',
        GPBType::FIXED64  => 'fixed64',
        GPBType::FIXED32  => 'fixed32',
        GPBType::BOOL     => 'bool',
        GPBType::STRING   => 'string',
        GPBType::GROUP    => 'group',   // 10
        GPBType::BYTES    => 'bytes',
        GPBType::UINT32   => 'uint32',
        GPBType::SFIXED32 => 'sfixed32',
        GPBType::SFIXED64 => 'sfixed64',
        GPBType::SINT32   => 'sint32',
        GPBType::SINT64   => 'sint64',
    );

    private static $_typeSimpleName = array(
        GPBType::DOUBLE   => 'Double',
        GPBType::FLOAT    => 'Float',
        GPBType::INT64    => 'Int64',
        GPBType::UINT64   => 'UInt64',
        GPBType::INT32    => 'Int32',
        GPBType::FIXED64  => 'Fixed64',
        GPBType::FIXED32  => 'Fixed32',
        GPBType::BOOL     => 'Bool',
        GPBType::STRING   => 'String',
        GPBType::GROUP    => 'Group',   // 10
        GPBType::MESSAGE  => 'Message', // 11
        GPBType::BYTES    => 'Bytes',
        GPBType::UINT32   => 'UInt32',
        GPBType::ENUM     => 'Enum',
        GPBType::SFIXED32 => 'SFixed32',
        GPBType::SFIXED64 => 'SFixed64',
        GPBType::SINT32   => 'SInt32',
        GPBType::SINT64   => 'SInt64',
    );

    
    private static $_phpTypesByProtobufType = array(
        GPBType::DOUBLE   => 'double',
        GPBType::FLOAT    => 'double',
        GPBType::INT32    => 'integer',
        GPBType::INT64    => 'integer',
        GPBType::UINT32   => 'integer',
        GPBType::UINT64   => 'integer',
        GPBType::SINT32   => 'integer',
        GPBType::SINT64   => 'integer',
        GPBType::FIXED32  => 'integer',
        GPBType::FIXED64  => 'integer',
        GPBType::SFIXED32 => 'integer',
        GPBType::SFIXED64 => 'integer',
        GPBType::BOOL     => 'boolean',
        GPBType::STRING   => 'string',
        GPBType::BYTES    => 'string',
        GPBType::ENUM     => 'integer',
        GPBType::MESSAGE  => 'object'
    );

    /**
     * Returns PHP type
     *
     * @return string|null
     */
    public function getPhpType()
    {
        if (isset(self::$_phpTypesByProtobufType[$this->getType()])) {
            return self::$_phpTypesByProtobufType[$this->getType()];
        } else {
            return null;
        }
    }

    public function getProtoTypeName()
    {
        if (isset(self::$_protoTypeName[$this->getType()])) {
            return self::$_protoTypeName[$this->getType()];
        }
        return $this->getTypeName();
    }

    public function __construct()
    {
        $this->public_desc = new \Google\Protobuf\FieldDescriptor($this);
    }

    public function setOneofIndex($index)
    {
        $this->oneof_index = $index;
    }

    public function getOneofIndex()
    {
        return $this->oneof_index;
    }

    public function setJsonName($json_name)
    {
        $this->json_name = $json_name;
    }

    public function getJsonName()
    {
        return $this->json_name;
    }

    public function setSetter($setter)
    {
        $this->setter = $setter;
    }

    public function getSetter()
    {
        return $this->setter;
    }

    public function setGetter($getter)
    {
        $this->getter = $getter;
    }

    public function getGetter()
    {
        return $this->getter;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function isRepeated()
    {
        return $this->label === GPBLabel::REPEATED;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setMessageType($message_type)
    {
        $this->message_type = $message_type;
    }

    /**
     * @return Descriptor
     */
    public function getMessageType()
    {
        return $this->message_type;
    }

    public function setEnumType($enum_type)
    {
        $this->enum_type = $enum_type;
    }

    public function getEnumType()
    {
        return $this->enum_type;
    }

    public function setPacked($packed)
    {
        $this->packed = $packed;
    }

    public function getPacked()
    {
        return $this->packed;
    }

    public function isPackable()
    {
        return $this->isRepeated() && self::isTypePackable($this->type);
    }

    public function isMap()
    {
        return $this->getType() == GPBType::MESSAGE &&
               !is_null($this->getMessageType()->getOptions()) &&
               $this->getMessageType()->getOptions()->getMapEntry();
    }

    public function isTimestamp()
    {
        return $this->getType() == GPBType::MESSAGE &&
            $this->getMessageType()->getClass() === "Google\Protobuf\Timestamp";
    }

    private static function isTypePackable($field_type)
    {
        return ($field_type !== GPBType::STRING  &&
            $field_type !== GPBType::GROUP   &&
            $field_type !== GPBType::MESSAGE &&
            $field_type !== GPBType::BYTES);
    }

    /**
     * @param FieldDescriptorProto $proto
     *
     * @return FieldDescriptor
     */
    public static function getFieldDescriptor($proto)
    {
        $type_name = null;
        $type = $proto->getType();
        switch ($type) {
            case GPBType::MESSAGE:
            case GPBType::GROUP:
            case GPBType::ENUM:
                $type_name = $proto->getTypeName();
                break;
            default:
                break;
        }

        $oneof_index = $proto->hasOneofIndex() ? $proto->getOneofIndex() : -1;
        $packed = false;
        $options = $proto->getOptions();
        if ($options !== null) {
            $packed = $options->getPacked();
        }

        $field = new FieldDescriptor();
        $field->setName($proto->getName());

        $json_name = $proto->hasJsonName() ? $proto->getJsonName() :
            lcfirst(implode('', array_map('ucwords', explode('_', $proto->getName()))));
        if ($proto->hasJsonName()) {
            $json_name = $proto->getJsonName();
        } else {
            $proto_name = $proto->getName();
            $json_name = implode('', array_map('ucwords', explode('_', $proto_name)));
            if ($proto_name[0] !== "_" && !ctype_upper($proto_name[0])) {
                $json_name = lcfirst($json_name);
            }
        }
        $field->setJsonName($json_name);

        $camel_name = implode('', array_map('ucwords', explode('_', $proto->getName())));
        $field->setGetter('get' . $camel_name);
        $field->setSetter('set' . $camel_name);
        $field->setType($proto->getType());
        $field->setNumber($proto->getNumber());
        $field->setLabel($proto->getLabel());
        $field->setPacked($packed);
        $field->setTypeName($proto->getTypeName());
        $field->setOneofIndex($oneof_index);

        // At this time, the message/enum type may have not been added to pool.
        // So we use the type name as place holder and will replace it with the
        // actual descriptor in cross building.
        switch ($type) {
            case GPBType::MESSAGE:
                $field->setMessageType($type_name);
                break;
            case GPBType::ENUM:
                $field->setEnumType($type_name);
                break;
            default:
                break;
        }

        return $field;
    }

    public static function buildFromProto($proto)
    {
        return FieldDescriptor::getFieldDescriptor($proto);
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return $this->typeName;
    }

    /**
     * @param string $typeName
     */
    public function setTypeName(string $typeName)
    {
        $this->typeName = $typeName;
    }
}
