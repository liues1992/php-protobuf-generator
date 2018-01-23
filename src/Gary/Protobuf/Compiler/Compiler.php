<?php

namespace Gary\Protobuf\Compiler;

use Gary\Protobuf\Generator\Logger;
use Gary\Protobuf\Generator\PhpMsgGenerator;
use Gary\Protobuf\Internal\DescriptorBuilder;
use Google\Protobuf\Internal\CodeGeneratorRequest;
use Google\Protobuf\Internal\CodeGeneratorResponse;
use Google\Protobuf\Internal\CodeGeneratorResponse_File;

class Compiler
{
    const MINIMUM_PROTOC_VERSION = '3.4.0';
    const VERSION = '0.11.0';

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
        $this->generateWithReqData($data);
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
            'action'      => 'StoreString',
            'multiple'    => false,
            'description' => 'The directory in which to search for imports.',
        ));

        $parser->addOption('protoc', array(
            'long_name'   => '--protoc',
            'action'      => 'StoreString',
            'default'     => 'protoc',
            'description' => 'The protoc compiler executable path.',
        ));
        $parser->addOption('grpc_out', array(
            'long_name'   => '--grpc_out',
            'action'      => 'StoreString',
            'default'     => false,
            'description' => 'generator grpc code out put path, default false',
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

    public function run()
    {
        $result = $this->parseArguments();

        $protocExecutable = $result->options['protoc'];
        $this->checkProtoc($protocExecutable);

        $cmd[] = $protocExecutable;
        $cmd[] = '--plugin=protoc-gen-custom=' . escapeshellarg(PROJECT_ROOT . '/protoc-gen-plugin.php');//$pluginExecutable);
        $grpcOut = $result->options['grpc_out'];
        if ($grpcOut) {
            $cmd[] = '--plugin=protoc-gen-custom-grpc=' . escapeshellarg(PROJECT_ROOT . '/protoc-gen-grpc-plugin.php');//$pluginExecutable);
            $cmd[] = '--custom-grpc_out=' . escapeshellarg($grpcOut);
        }


        if ($result->options['proto_path']) {
            $cmd[] = '--proto_path=' . escapeshellarg($result->options['proto_path']);
        }

        $cmd[] = '--custom_out=' . escapeshellarg(':' . $result->options['out']);

        foreach ($result->args['file'] as $file) {
            $cmd[] = escapeshellarg($file);
        }

        $cmdStr = implode(' ', $cmd);
        passthru("set -x; " . $cmdStr, $return);

        if ($return !== 0) {
            Logger::error('protoc exited with an exit status ' . $return . ' when executed with: ' . PHP_EOL
                . '  ' . implode(" \\\n    ", $cmd));
            exit($return);
        }
    }

    /**
     * @param string $data
     */
    private function generateWithReqData($data)
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
            echo $response->serializeToString();
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