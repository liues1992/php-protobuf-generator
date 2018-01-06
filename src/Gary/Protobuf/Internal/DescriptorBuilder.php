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

use Google\Protobuf\Internal\DescriptorPool;
use Google\Protobuf\Internal\FileDescriptorProto;
use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\MessageBuilderContext;
use Google\Protobuf\Internal\EnumBuilderContext;

class DescriptorBuilder
{
    private static $builder;
    private $realPool = null;

    public static function getBuilder()
    {
        if (!isset(self::$builder)) {
            self::$builder = new DescriptorBuilder();
        }
        return self::$builder;
    }

    function __construct()
    {
        $this->realPool = DescriptorPool::getGeneratedPool();
    }

    /**
     * @param FileDescriptorProto[] $protos
     *
     * @return array
     */
    public function getFileDescriptors($protos)
    {
        $files = [];
        foreach ($protos as $proto) {
            $file = FileDescriptor::buildFromProto($proto);

            foreach ($file->getMessageType() as $desc) {
                $this->addDescriptor($desc);
            }
            unset($desc);

            foreach ($file->getEnumType() as $desc) {
                $this->addEnumDescriptor($desc);
            }
            unset($desc);

            foreach ($file->getMessageType() as $desc) {
                $this->crossLink($desc);
            }

            foreach ($file->getService() as $service) {
                $this->crossLinkService($service);
            }
            unset($desc);
            $files[] = $file;
        }
        return $files;
    }


    /**
     * @param Descriptor $descriptor
     */
    public function addDescriptor($descriptor)
    {
        $this->realPool->addDescriptor($descriptor);
    }

    /**
     * @param EnumDescriptor $descriptor
     */
    public function addEnumDescriptor($descriptor)
    {
        $this->realPool->addEnumDescriptor($descriptor);
    }


    public function getDescriptorByProtoName($proto)
    {
        return $this->realPool->getDescriptorByProtoName($proto);
    }

    public function getEnumDescriptorByProtoName($proto)
    {
        return $this->realPool->getEnumDescriptorByProtoName($proto);
    }

    private function crossLinkService(ServiceDescriptor $serviceDescriptor)
    {
        foreach ($serviceDescriptor->getMethods() as $method) {
            if (is_string($method->getInputType())) {
                $method->setInputType(
                    $this->getDescriptorByProtoName($method->getInputType())
                );
            }
            if (is_string($method->getOutputType())) {
                $method->setOutputType(
                    $this->getDescriptorByProtoName($method->getOutputType())
                );
            }
        }
    }
    private function crossLink(Descriptor $desc)
    {
        /** @var FieldDescriptor $field */
        foreach ($desc->getField() as $field) {
            switch ($field->getType()) {
                case GPBType::MESSAGE:
                    $proto = $field->getMessageType();
                    if (is_string($proto)) {
                        $field->setMessageType(
                            $this->getDescriptorByProtoName($proto));
                    }
                    break;
                case GPBType::ENUM:
                    $proto = $field->getEnumType();
                    if (is_string($proto)) {
                        $field->setEnumType(
                            $this->getEnumDescriptorByProtoName($proto));
                    }
                    break;
                default:
                    break;
            }
        }
        unset($field);

        foreach ($desc->getNestedType() as $nested_type) {
            $this->crossLink($nested_type);
        }
        unset($nested_type);
    }

}
