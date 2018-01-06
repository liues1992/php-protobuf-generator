<?php

namespace Gary\Protobuf\Compiler;

use Gary\Protobuf\Generator\Logger;
use Gary\Protobuf\Generator\PhpGenerator;
use Gary\Protobuf\Internal\DescriptorBuilder;
use Google\Protobuf\Internal\CodeGeneratorRequest;
use Google\Protobuf\Internal\CodeGeneratorResponse;
use Google\Protobuf\Internal\CodeGeneratorResponse_File;

class Compiler
{
    const MINIMUM_PROTOC_VERSION = '3.4.0';
    const VERSION = '0.11.0';
    const REQUEST_DATA_PATH = '/tmp/protoc_code_gen_req';

    /**
     * @var CodeGeneratorInterface
     */
    private $generator = null;

    /**
     * @param CodeGeneratorInterface $generator
     */
    public function setGenerator(CodeGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function runAsPlugin()
    {
        $data = file_get_contents('php://stdin');
        $this->generateWithReqData($data, null, true);
    }

    public function runAsBridge()
    {
        $data = file_get_contents('php://stdin');
        file_put_contents(self::REQUEST_DATA_PATH, $data);
    }


    /**
     * @return \Console_CommandLine_Result
     */
    private function parseArguments()
    {
        $parser = new \Console_CommandLine(array('version' => self::VERSION));

        $parser->addOption('out', array(
            'short_name'  => '-o',
            'long_name'   => '--out',
            'action'      => 'StoreString',
            'default'     => './',
            'description' => 'The destination directory for generated files (defaults to the current directory).',
        ));

        $parser->addOption('proto_path', array(
            'short_name'  => '-I',
            'long_name'   => '--proto_path',
            'action'      => 'StoreArray',
            'multiple'    => true,
            'description' => 'The directory in which to search for imports.',
        ));

        $parser->addOption('protoc', array(
            'long_name'   => '--protoc',
            'action'      => 'StoreString',
            'default'     => 'protoc',
            'description' => 'The protoc compiler executable path.',
        ));

        $parser->addArgument('file', array(
            'multiple'    => true,
            'description' => 'Proto files.',
        ));

        try {
            $result = $parser->parse();
            return $result;
        } catch (\Exception $e) {
            $parser->displayError($e->getMessage());
            exit(1);
        }
    }

    /**
     * @param string $pluginExecutable
     */
    public function run($pluginExecutable)
    {
        $result = $this->parseArguments();

        $protocExecutable = $result->options['protoc'];
        $this->checkProtoc($protocExecutable);

        $cmd[] = $protocExecutable;
        $cmd[] = '--plugin=protoc-gen-custom=' . escapeshellarg($pluginExecutable);

        if ($result->options['proto_path']) {
            foreach ($result->options['proto_path'] as $protoPath) {
                $cmd[] = '--proto_path=' . escapeshellarg($protoPath);
            }
        }

        $cmd[] = '--custom_out=' . escapeshellarg(':' . $result->options['out']);

        foreach ($result->args['file'] as $file) {
            $cmd[] = escapeshellarg($file);
        }

        $cmdStr = implode(' ', $cmd);
        passthru($cmdStr, $return);

        if ($return !== 0) {
            Logger::error('protoc exited with an exit status ' . $return . ' when executed with: ' . PHP_EOL
                . '  ' . implode(" \\\n    ", $cmd));
            exit($return);
        } else {
            $requestData = file_get_contents(self::REQUEST_DATA_PATH);
            if (!$requestData) {
                Logger::error("cannot get protoc request");
                exit(-1);
            }
            $this->generateWithReqData($requestData, $result, false);
        }
    }

    /**
     * @param                             $data
     * @param \Console_CommandLine_Result $args
     * @param                             $isPlugin
     */
    private function generateWithReqData($data, $args, $isPlugin)
    {
        $request = new CodeGeneratorRequest();
        try {
            $request->mergeFromString($data);
        } catch (\Exception $ex) {
            Logger::error('Unable to parse a message passed by protoc.' . $ex->getMessage() . '.');
            exit(1);
        }
        $generator = $this->generator;
        try {
            $fileDescriptors = DescriptorBuilder::getBuilder()
                ->getFileDescriptors(iterator_to_array($request->getProtoFile()->getIterator()));
            $response = $generator->generate($request, $fileDescriptors);
            /** @var CodeGeneratorResponse_File $file */
            if ($isPlugin) {
                echo $response->serializeToString();
            } else {
                foreach ($response->getFile() as $file) {
                    $name = rtrim($args->options['out'], "/") . "/" . $file->getName();
                    $dirname = dirname($name);
                    if (!is_dir($dirname)) {
                        mkdir($dirname, 0777, true);
                    }
                    if ($file->getInsertionPoint()) {
                        if (!file_exists($name)) {
                            Logger::error("file must exist to apply a insertion point: " .
                                $file->getInsertionPoint() . ", file name $name");
                            exit(-1);
                        } else {
                            $point = $file->getInsertionPoint();
                            str_replace("@@protoc_insertion_point($point)",
                                $file->getContent(), file_get_contents($name));
                        }
                    } else {
                        file_put_contents(
                            $name,
                            $file->getContent());
                    }
                }
            }
        } catch (\Exception $ex) {
            Logger::error($ex->getMessage() . '.');
            exit(1);
        }
    }


    /**
     * @param string $protocExecutable
     *
     * @return null
     */
    private function checkProtoc($protocExecutable)
    {
        exec("$protocExecutable --version", $output, $return);

        if (0 !== $return && 1 !== $return) {
            Logger::error('Unable to find the protoc command. Please make sure it\'s installed and available in the path.');
            exit(1);
        }

        if (!preg_match('/[0-9\.]+/', $output[0], $m)) {
            Logger::error('Unable to get protoc command version. Please make sure it\'s installed and available in the path.');
            exit(1);
        }

        if (version_compare($m[0], self::MINIMUM_PROTOC_VERSION) < 0) {
            Logger::error('The protoc command in your system is too old. Minimum required version is '
                . self::MINIMUM_PROTOC_VERSION . ' but found ' . $m[0]);
            exit(1);
        }
    }

}