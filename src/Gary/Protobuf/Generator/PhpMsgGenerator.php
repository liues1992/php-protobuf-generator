<?php

namespace Gary\Protobuf\Generator;

use Gary\Protobuf\Compiler\CodeGeneratorInterface;
use Gary\Protobuf\Internal\DescriptorBuilder;
use Gary\Protobuf\Internal\FieldDescriptor;
use Gary\Protobuf\Internal\FileDescriptor;
use Gary\Protobuf\Internal\MethodDescriptor;
use Gary\Protobuf\Internal\ServiceDescriptor;
use Google\Protobuf\Internal\CodeGeneratorRequest;
use Google\Protobuf\Internal\CodeGeneratorResponse;
use Google\Protobuf\Internal\CodeGeneratorResponse_File;
use Gary\Protobuf\Internal\Descriptor;
use Gary\Protobuf\Internal\EnumDescriptor;
use Google\Protobuf\Internal\EnumValueDescriptorProto;
use Google\Protobuf\Internal\FileDescriptorProto;
use Google\Protobuf\Internal\FileDescriptorSet;
use Google\Protobuf\Internal\GPBType;
use PHPUnit\Framework\MockObject\RuntimeException;

class PhpMsgGenerator implements CodeGeneratorInterface
{
    const CLASS_NAME_SEPARATOR = '_';
    const PHP_NAMESPACE_SEPARATOR = '\\';
    const PB_NAMESPACE_SEPARATOR = '.';

    const TAB = '    ';
    const EOL = PHP_EOL;

    /** @var  FileDescriptor[] */
    private $fileDescriptors;
    /**
     * @var CodeGeneratorResponse_File[]
     */
    private $responseFiles = [];
    private $customArguments = [];

    private function generateClass($shortName, $namespace, $content)
    {
        $file = new CodeGeneratorResponse_File();
        $file->setName($this->getClassFilename($shortName, $namespace));
        $file->setContent('<?php' . PHP_EOL . $content);
        $this->responseFiles[] = $file;
    }

    private function generateClassWithFullName($name, $content)
    {
        $file = new CodeGeneratorResponse_File();
        $path = str_replace(
            '\\',
            '/',
            $name
        );
        $file->setName($path . ".php");
        $file->setContent('<?php' . PHP_EOL . $content);
        $this->responseFiles[] = $file;
    }


    /**
     * @param CodeGeneratorRequest $request
     * @param FileDescriptor[]     $fileDescriptors
     *
     * @return CodeGeneratorResponse
     */
    public function generate(CodeGeneratorRequest $request, $fileDescriptors): CodeGeneratorResponse
    {
        $this->parseParameter($request->getParameter());

        $this->genMetadata($request);

        $filesToGenerate = iterator_to_array($request->getFileToGenerate());
        foreach ($fileDescriptors as $i => $fileDescriptor) {
            if (!in_array($fileDescriptor->getName(), $filesToGenerate)) {
                continue;
            }
            $this->_generateFiles($fileDescriptor);
        }

        $response = new CodeGeneratorResponse();
        $response->setFile($this->responseFiles);
        return $response;
    }

    private function parseParameter($parameter_str)
    {
        $parameters = explode(',', $parameter_str);

        foreach ($parameters as $parameter) {
            $parts = explode('=', $parameter);
            if (count($parts) === 2) {
                $name = trim($parts[0]);
                $value = trim($parts[1]);

                $this->customArguments[$name] = $value;
            }
        }
    }

    private function getClassFilename($className, $namespaceName)
    {
        if ($namespaceName) {
            $baseName = str_replace(
                PhpMsgGenerator::PHP_NAMESPACE_SEPARATOR,
                DIRECTORY_SEPARATOR,
                $namespaceName . PhpMsgGenerator::PHP_NAMESPACE_SEPARATOR . $className
            );
        } else {
            $baseName = $className;
        }
        return $baseName . '.php';
    }

    private function genMetadata(CodeGeneratorRequest $request)
    {
        $filesToGenerate = iterator_to_array($request->getFileToGenerate());
        /** @var FileDescriptorProto $proto */
        foreach ($request->getProtoFile() as $proto) {
            if (!in_array($proto->getName(), $filesToGenerate)) {
                continue;
            }
            // clone proto to avoid affecting other logic using the source_code_info
            $proto = clone $proto;
            $proto->setSourceCodeInfo(null);

            $p = new FileDescriptorSet();
            $p->getFile()[] = $proto;

            $buffer = new CodeStringBuffer(self::TAB, self::EOL);
            $filename = $proto->getName();
            $filenameNoExt = preg_replace('/(.*)(\.[\w\d]+)$/', '$1', $proto->getName()); // file name without suffix
            $filenameParts = array_map('ucfirst', explode('/', $filenameNoExt));
            array_unshift($filenameParts, 'GPBMetadata');
            $shortClassName = $filenameParts[count($filenameParts) - 1];
            array_pop($filenameParts);
            $nameSpace = implode("\\", $filenameParts);


            $binary = $p->serializeToString();

            $str = <<<TAG
# Generated by the php-protobuf-generator.  DO NOT EDIT!
# source: $filename

namespace $nameSpace;

class $shortClassName
{
    public static \$is_initialized = false;

    public static function initOnce() {
        \$pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::\$is_initialized == true) {
          return;
        }
TAG;
            $buffer->append($str);
            $buffer->incrIndentation(2);
            foreach ($proto->getDependency() as $dependency) {
                $buffer->append($this->_initCodeWithFilename($dependency));
            }
            $buffer->append("\$pool->internalAddGeneratedFile(");
            $hexStr = bin2hex($binary);
            $hexArr = str_split($hexStr, 30);

            $escapeHexArr = [];
            foreach ($hexArr as $hex) {
                $escapeHex = "";
                for ($i = 0; $i < strlen($hex); $i += 2) {
                    $escapeHex .= "\\x".$hex[$i].$hex[$i+1];

                }
                $escapeHexArr[] = $escapeHex;
            }

            $hexRes = implode("\" .\n            \"", $escapeHexArr);
            $buffer->append('    "' . $hexRes . '"', true);
            $buffer->append(");");
            $buffer->append("static::\$is_initialized = true;");
            $buffer->decrIndentation();
            $buffer->append("}");
            $buffer->decrIndentation();
            $buffer->append("}");
            $this->generateClass($shortClassName, $nameSpace, $buffer->__toString());
        }

    }

    /**
     * @param FileDescriptor   $file
     * @param CodeStringBuffer $buffer
     */
    private function _createFileComment(FileDescriptor $file, $buffer)
    {
        $buffer->append("# Generated by the php-protobuf-generator.  DO NOT EDIT!");
        $buffer->append("# source: " . $file->getName());
    }

    /**
     * @param FileDescriptor $file
     */
    private function _generateFiles($file)
    {
        foreach ($file->getEnumType() as $descriptor) {
            $this->_createEnum($descriptor, $file);
        }

        /** @var Descriptor $descriptor */
        foreach ($file->getMessageType() as $i => $descriptor) {
            $this->_createClass($descriptor, $file);
        }
    }

    /**
     * @param Descriptor     $descriptor
     * @param FileDescriptor $file
     *
     * @throws \Exception
     */
    private function _createClass(
        Descriptor $descriptor, FileDescriptor $file)
    {
        foreach ($descriptor->getEnumType() as $enum) {
            $this->_createEnum($enum, $file);
        }

        /** @var Descriptor $nested */
        foreach ($descriptor->getNestedType() as $i => $nested) {
            if (!$nested->isMapEntry()) {
                $this->_createClass($nested, $file);
            }
        }

        /**
         * namespace & use
         */
        $buffer = new CodeStringBuffer(self::TAB, self::EOL);
        $this->_createFileComment($file, $buffer);
        $buffer->newline();

        $namespaceName = $this->_createNamespaceName($descriptor);
        if ($namespaceName) {
            $buffer->append('namespace ' . $namespaceName . ';')->newline();
        }
        $buffer->append('use Google\Protobuf\Internal\GPBType;');
        $buffer->append('use Google\Protobuf\Internal\RepeatedField;');
        $buffer->append('use Google\Protobuf\Internal\GPBUtil;');
        $buffer->newline();

        /**
         * class comment
         */
        $comment = new CommentStringBuffer(self::TAB, self::EOL);
        $path = $this->_createEmbeddedMessagePath($descriptor);
        if ($path) {
            $comment->append($descriptor->getFullName() . ' message embedded in ' . $path . ' message');
        } else {
            $comment->append($descriptor->getFullName() . ' message');
        }


        $location = $file->getSourceCodeLocation($descriptor);
        if (!$location) {
            throw  new \Exception("cannot find source code location:" . $descriptor->getFullName()
                . " path: " . implode(", ", $descriptor->getSourceCodePath()));
        }
        if ($location->getLeadingComments()) {
            $comment->append($location->getLeadingComments());
        }
        if ($location->getTrailingComments()) {
            $comment->append($location->getTrailingComments());
        }
        $buffer->append($comment);

        /**
         * class name
         */
        $fullName = $this->_createClassName($descriptor);
        $shortName = $this->getClassShortName($fullName);
        $baseMessage = $this->customArguments['base_message'] ?? '';
        if (!$baseMessage) {
            $baseMessage = '\Google\Protobuf\Internal\Message';
        }
        $buffer->append("class $shortName extends $baseMessage")
            ->append('{')
            ->incrIndentation();

        $this->_createClassBody($descriptor->getField(), $buffer, $file);

        $buffer->decrIndentation()
            ->append('}');

        $this->generateClass($shortName, $namespaceName, $buffer);
    }

    /**
     * @param EnumDescriptor $descriptor
     * @param                $file
     */
    private function _createEnum(
        EnumDescriptor $descriptor, FileDescriptor $file
    )
    {
        $buffer = new CodeStringBuffer(self::TAB, self::EOL);
        $this->_createFileComment($file, $buffer);
        $buffer->newline();

        $className = $descriptor->getClass();
        $shortName = $this->getClassShortName($className);
        $namespaceName = $this->_createNamespaceName($descriptor);
        if ($namespaceName) {
            $buffer->append('namespace ' . $namespaceName . ";");
        } else {
//            $buffer->append('namespace {');
        }
        $buffer->newline();

        $comment = new CommentStringBuffer(self::TAB, self::EOL);
        $comment->alignWithBuffer($buffer);
        $path = $this->_createEmbeddedMessagePath($descriptor);
        if ($path) {
            $comment->append($descriptor->getClass() . ' enum embedded in ' . $path . ' message');
        } else {
            $comment->append($descriptor->getClass() . ' enum');
        }

        $buffer->append($comment);

        $buffer->append('final class ' . $shortName)
            ->append('{')
            ->incrIndentation();

        $this->_createEnumClassDefinition($descriptor->values(), $buffer);

        $buffer->decrIndentation()
            ->append('}');

//        $buffer->append('}');

        $this->generateClassWithFullName($className, $buffer);
    }

    /**
     * @param Descriptor|EnumDescriptor $descriptor
     *
     * @return null|string
     */
    private function _createNamespaceName($descriptor)
    {
        if (isset($this->customArguments['namespace'])) {
            return $this->customArguments['namespace'];
        }

        $t = $descriptor->getClass();
        $t = explode("\\", $t);
        array_pop($t);
        $name = implode("\\", $t);
        return $name;
    }

    /**
     * @param Descriptor $descriptor
     *
     * @return string
     */
    private function _createClassName($descriptor)
    {
        return $descriptor->getClass();
    }


    private function getClassShortName($fullName)
    {
        $n = explode("\\", $fullName);
        $n = array_pop($n);
        return $n;
    }

    /**
     * Creates embedded message path composed of ancestor messages
     * separated by slash "/". If message has no ancestor returns empty string.
     *
     * @param Descriptor|EnumDescriptor $descriptor
     *
     * @return string
     */
    private function _createEmbeddedMessagePath($descriptor)
    {
        $containing = $descriptor->getContaining();
        $path = array();

        while ($containing && !$containing instanceof FileDescriptor) {
            /**
             * @var Descriptor $containing
             */
            $path[] = $containing->getFullName();
            $containing = $containing->getContaining();
        }

        array_reverse($path);

        return implode("/", $path);
    }

    /**
     * @param FieldDescriptor[] $fields
     * @param CodeStringBuffer  $buffer
     * @param                   $file
     */
    private function _createClassBody(array $fields, CodeStringBuffer $buffer, FileDescriptor $file)
    {
        foreach ($fields as $i => $field) {
            $comment = new CommentStringBuffer(self::TAB, self::EOL);
            $comment->setIndentLevel($buffer->getIndentLevel());

            $this->appendCommentFromSourceCode($comment, $file, $field);
            $buffer->append($comment);
            $defaultValue = 'null';
            $msgType = $field->getType();
            switch ($msgType) {
                case GPBType::DOUBLE :
                case GPBType::FLOAT     :
                case GPBType::FIXED64   :
                case GPBType::FIXED32   :
                case GPBType::SFIXED32  :
                case GPBType::SFIXED64  :
                    $defaultValue = '0.0';
                    break;

                case GPBType::SINT32    :
                case GPBType::INT64     :
                case GPBType::SINT64    :
                case GPBType::UINT64    :
                case GPBType::INT32     :
                case GPBType::UINT32    :
                case GPBType::ENUM      :
                    $defaultValue = '0';
                    break;
                case GPBType::BOOL      :
                    $defaultValue = 'false';
                    break;
                case GPBType::BYTES     :
                case GPBType::STRING    :
                    $defaultValue = "''";
                    break;
                case GPBType::GROUP     :
                case GPBType::MESSAGE   :
                default:
            }
            if (($field->isRepeated() || $field->isMap())) {
                $defaultValue = '[]';
            }
            $buffer->append('private $' . $field->getName() . " = $defaultValue;")->newline();
        }

        $this->_createClassConstructor($buffer, $file);

        foreach ($fields as $field) {
            $this->_describeSingleField($field, $buffer, $file);
        }
    }

    /**
     * @param CommentStringBuffer $comment
     * @param FileDescriptor      $file
     * @param object              $descriptor
     * @param bool                $appendCodeInfo
     */
    private function appendCommentFromSourceCode(CommentStringBuffer $comment,
                                                 FileDescriptor $file, $descriptor, $appendCodeInfo = true)
    {
        $location = $file->getSourceCodeLocation($descriptor);
        if (!$location) {
            throw new \RuntimeException("cannot get location, path: " . var_export($descriptor->getSourceCodePath(), true));
        }
        if ($location->getLeadingComments()) {
            $comment->append($location->getLeadingComments());
            if ($appendCodeInfo) {
                $comment->newline();
            }
        }

        if ($location->getTrailingComments()) {
            $comment->append($location->getTrailingComments());
            if ($appendCodeInfo) {
                $comment->newline();
            }
        }
        if ($appendCodeInfo) {
            if ($descriptor instanceof FieldDescriptor) {
                $preType = '';
                if ($descriptor->isRepeated()) {
                    $preType = 'repeated ';
                }
                $code = sprintf("%s%s %s = %s", $preType, $descriptor->getProtoTypeName(), $descriptor->getName(), $descriptor->getNumber());
                $comment->append(sprintf("Generated from protobuf <code>$code</code>"));
            }
        }
    }

    /**
     * @param FieldDescriptor  $field
     * @param CodeStringBuffer $buffer
     * @param FileDescriptor   $file
     */
    private function _describeSingleField(FieldDescriptor $field, CodeStringBuffer $buffer, FileDescriptor $file)
    {
        $phpType = $field->getPhpType();
        if ($phpType != 'object') {
            $typeName = $phpType;
        } else {
            $typeName = '\\' . $field->getMessageType()->getClass();
        }

        /**
         * Set
         */
        if ($field->isMap()) {
            $retType = '\Google\Protobuf\Internal\MapField';
        } else if ($field->isRepeated()) {
            $retType = $typeName . '[]|RepeatedField';
        } else {
            $retType = $typeName;
        }
        $comment = new CommentStringBuffer(self::TAB, self::EOL);
        $comment->alignWithBuffer($buffer);
        $this->appendCommentFromSourceCode($comment, $file, $field);
        $comment->appendParam(
            'param',
            $retType . ' $value'
        )
            ->appendParam('return', '$this');

        if ($field->isMap()) {
            $valueField = $field->getMessageType()->getFieldByNumber(2);
            if ($valueField->getType() == GPBType::MESSAGE) {
                $tmpType = 'GPBType::MESSAGE, \\' . $valueField->getMessageType()->getClass() . "::class";
            } else {
                $tmpType =
                    'GPBType::' . strtoupper($field->getMessageType()->getFieldByNumber(1)->getSimpleTypeName());
            }
            $checkLine = sprintf('$value = GPBUtil::checkMapField($value, GPBType::%s, %s);',
                strtoupper($field->getMessageType()->getFieldByNumber(1)->getSimpleTypeName()),
                $tmpType
            );
        } else if ($field->isRepeated()) {
            if ($field->getType() == GPBType::MESSAGE) {
                $checkLine = sprintf('$value = GPBUtil::checkRepeatedField($value, GPBType::%s, %s::class);',
                    strtoupper($field->getSimpleTypeName()),
                    '\\' . $field->getMessageType()->getClass()
                );
            } else {
                $checkLine = sprintf('$value = GPBUtil::checkRepeatedField($value, GPBType::%s);',
                    strtoupper($field->getSimpleTypeName()));
            }
        } else if ($field->getType() == GPBType::MESSAGE) {
            $checkLine = 'GPBUtil::checkMessage($value, \\' . $field->getMessageType()->getClass() . '::class);';
        } else {
            $simpleTypeName = $field->getSimpleTypeName();
            $simpleTypeName = str_replace("SInt", "Int", $simpleTypeName);
            $simpleTypeName = str_replace("SFixed", "Int", $simpleTypeName);
            $simpleTypeName = str_replace("Fixed", "UInt", $simpleTypeName);
            if ($field->getType() == GPBType::STRING) {
                $checkLine = 'GPBUtil::check' . $simpleTypeName . '($value, true);';
            } else if ($field->getType() == GPBType::BYTES) {
                $checkLine = 'GPBUtil::checkString($value, false);';
            } else {
                $checkLine = 'GPBUtil::check' . $simpleTypeName . '($value);';
            }
        }
        $buffer
            ->append($comment)
            ->append(
                'public function set' . ucfirst($field->getJsonName()) .
                '($value)'
            )
            ->append('{')
            ->incrIndentation()
            ->append($checkLine)
            ->append('$this->' . $field->getName() . ' = $value;')
            ->append(
                'return $this;'
            )
            ->decrIndentation()
            ->append('}');

        /**
         * Getter
         */
        $comment = new CommentStringBuffer(self::TAB, self::EOL);
        $comment->setIndentLevel($buffer->getIndentLevel());
        $this->appendCommentFromSourceCode($comment, $file, $field);

        $comment->appendParam('return', $retType);

        $buffer->newline()
            ->append($comment)
            ->append('public function get' . ucfirst($field->getJsonName()) . '()')
            ->append('{')
            ->incrIndentation();
        $buffer->append('return ' . '$this->' . $field->getName() . ';');
        $buffer->decrIndentation()
            ->append('}')->newline();
    }

    /**
     * @param EnumValueDescriptorProto[] $enums
     * @param CodeStringBuffer           $buffer
     */
    private function _createEnumClassDefinition(array $enums, CodeStringBuffer $buffer)
    {
        foreach ($enums as $enum) {
            $buffer->append(
                'const ' . $enum->getName() . ' = ' . $enum->getNumber() . ';'
            );
        }

        $buffer->newline();

        $comment = new CommentStringBuffer(self::TAB, self::EOL);
        $comment->alignWithBuffer($buffer);
        $comment->append('Returns defined enum values')
            ->newline()
            ->appendParam('return', 'int[]');

        $buffer->append($comment)
            ->append('public function getEnumValues()')
            ->append('{');

        $buffer->append('return array(', true, 1)
            ->incrIndentation()
            ->incrIndentation();

        foreach ($enums as $enum) {
            $buffer->append('\'' . $enum->getName() . '\' => self::' . $enum->getName() . ',');
        }

        $buffer->decrIndentation()
            ->append(');');

        $buffer->decrIndentation()
            ->append('}');
    }

    /**
     * @param CodeStringBuffer $buffer
     * @param FileDescriptor   $file
     */
    private function _createClassConstructor(CodeStringBuffer $buffer, $file)
    {
        $buffer->append('public function __construct()')
            ->append('{')
            ->incrIndentation()
            ->append($this->_initCodeWithFilename($file->getName()))
            ->append('parent::__construct();')
            ->decrIndentation()
            ->append('}')
            ->newline();

        $comment = new CommentStringBuffer(self::TAB, self::EOL);
        $comment->append('Clears message values and sets default ones')
            ->newline()
            ->appendParam('return', 'null');
    }

    private function _initCodeWithFilename($filename)
    {
        if (strpos($filename, 'google/protobuf') === 0) {
            return '';
        }

        $name = preg_replace('/(.*)(\.[\w\d]+)$/', '$1', $filename);
        $fileNamespace = implode("\\", array_map('ucfirst', explode('/', $name)));
        return sprintf('\GPBMetadata\%s::initOnce();', $fileNamespace);
    }

}
