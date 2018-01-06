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

use Google\Protobuf\Internal\FileDescriptorProto;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\ServiceDescriptorProto;
use Google\Protobuf\Internal\SourceCodeInfo;
use Google\Protobuf\Internal\SourceCodeInfo_Location;

class FileDescriptor
{

    protected $service = [];
    private $package;
    private $message_type = [];
    private $enum_type = [];
    private $path_to_location = [];
    private $name;
    private $source_code_info;

    private $fileContent = [];

    public function setPackage($package)
    {
        $this->package = $package;
    }

    public function setName($name)
    {
        $this->name = $name;
        if (!file_exists($name)) {
            throw new \RuntimeException("file not found $name");
        }
        $content = file_get_contents($name);
        $content = str_replace("\r\n", "\n", $content);
        $content = str_replace("\t", "        ", $content);
        $this->fileContent = explode("\n", $content);
        return;
    }

    /**
     * @param RepeatedField $span
     *
     * @return string
     */
    public function codeBySpan($span)
    {
        $arr = iterator_to_array($span->getIterator());
        $startLine = (int)$arr[0];
        $startColumn = $arr[1];
        $endLine = (int)(isset($arr[3]) ? $arr[2] : $startLine);
        $endColumn = isset($arr[3]) ? $arr[3] : $arr[2];
        $line = isset($this->fileContent[$startLine]) ? $this->fileContent[$startLine] : null;
        if ($line === null) {
            throw new \RuntimeException("line $startLine not found in $this->name span :" . implode(",", $arr)
                . "; line " . ($startLine + 1) . " col : " . ($startColumn + 1) . " file line count : " . count($this->fileContent));
        }
        $result = '';
        for ($i = $startLine; $i <= $endLine; $i++) {
            $line = $this->fileContent[$i];
            if ($endLine > $startLine) {
                if ($i === $endLine) {
                    $result .= substr($line, 0, $endColumn + 1);
                } else if ($i === $startLine){
                    $result .= substr($line, $startColumn) . "\n";
                } else {
                    $result .= $line . "\n";
                }
            } else {
                $result .= substr($line, $startColumn, $endColumn - $startColumn + 1);
            }
        }

        return $result;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function getContaining()
    {
        return null;
    }
    
    public function getSourceCodePath()
    {
        return [];
    }
    
    /**
     * @return SourceCodeInfo
     */
    public function getSourceCodeInfo()
    {
        return $this->source_code_info;
    }

    /**
     * @param SourceCodeInfo $info
     */
    public function setSourceCodeInfo($info)
    {
        $this->source_code_info = $info;
        if ($info) {
            foreach ($info->getLocation() as $location) {
                /** @var SourceCodeInfo_Location $location  */
                $this->path_to_location[implode(",", iterator_to_array($location->getPath()->getIterator()))] = $location;
            }  
        }
    }

    /**
     * @param array $path
     *
     * @return SourceCodeInfo_Location
     */
    public function getSourceCodeLocationForPath(array $path)
    {
        $v =  isset($this->path_to_location[implode(",", $path)]) ? $this->path_to_location[implode(",", $path)] :  null ;
        if (!$v) {
//            throw new \Exception(var_export(array_keys($this->path_to_location), true));
        }
        return $v;
    }
    
    public function getPackage()
    {
        return $this->package;
    }

    public function getMessageType()
    {
        return $this->message_type;
    }

    public function addMessageType($desc)
    {
        $this->message_type[] = $desc;
    }

    public function getEnumType()
    {
        return $this->enum_type;
    }

    public function addEnumType($desc)
    {
        $this->enum_type[]= $desc;
    }

    public function setService($service)
    {
        $this->service = $service;
    }

    public function getService()
    {
        return $this->service;
    }

    /**
     * @param FileDescriptorProto $proto
     *
     * @return FileDescriptor
     */
    public static function buildFromProto($proto)
    {
        $file = new FileDescriptor();
        $file->setPackage($proto->getPackage());
        $file->setName($proto->getName());
        $file->setSourceCodeInfo($proto->getSourceCodeInfo());

        /** @var Descriptor $message_proto */
        foreach ($proto->getMessageType() as $i => $message_proto) {
            $m = Descriptor::buildFromProto(
                $message_proto, $proto, "");
            $m->setContaining($file);
            $m->setIndex($i);
            $file->addMessageType($m);
        }
        foreach ($proto->getEnumType() as $i => $enum_proto) {
            $m = EnumDescriptor::buildFromProto(
                $enum_proto,
                $proto,
                "");
            $m->setContaining($file);
            $m->setIndex($i);
            $file->addEnumType($m);
        }

        /** @var ServiceDescriptorProto $serviceProto */
        foreach ($proto->getService() as $i => $serviceProto) {
            $service = ServiceDescriptor::buildFromProto($serviceProto, $proto, "");
            $service->setContaining($file);
            $service->setIndex($i);
            $file->addService($service);
        }
        return $file;
    }

    /**
     * @return mixed
     */
    public function getFileContent()
    {
        return $this->fileContent;
    }

    public function addService($s)
    {
        $this->service[] = $s;
    }
}
