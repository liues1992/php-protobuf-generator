<?php
/**
 * Created by PhpStorm.
 * User: Gary
 * Date: 2018/1/6
 * Time: 13:39
 */

namespace Gary\Protobuf\Compiler;


use Gary\Protobuf\Internal\FileDescriptor;
use Google\Protobuf\Internal\CodeGeneratorRequest;
use Google\Protobuf\Internal\CodeGeneratorResponse;

interface CodeGeneratorInterface
{
    /**
     * @param CodeGeneratorRequest $request
     * @param FileDescriptor[]     $fileDescriptors
     *
     * @return CodeGeneratorResponse
     */
    public function generate(
        CodeGeneratorRequest $request,
        $fileDescriptors): CodeGeneratorResponse;
}