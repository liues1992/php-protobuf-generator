# Introduction 
Generate PHP protobuf code using PHP
`./protoc-gen.php -o build tests/test3.proto`

The generated message code is meant to work with Google protobuf's official PHP implementation:
https://github.com/google/protobuf/tree/master/php

The generated service client code is meant to  work with gRpc
https://grpc.io/docs/quickstart/php.html#prerequisites

# Requirements
- Unix/Linux system
- PHP >= 7.0 and composer installed
- Only support proto3 syntax proto file
- protoc installed, version >= 3.5

# Usage
- `composer require liues1992/php-protobuf-generator`
- `./vendor/bin/protoc-gen.php --out=build --grpc_out=build tests/*.proto` or use directly as plugin:

   `protoc --php-custom_out=build --plugin=protoc-gen-php-custom=./vendor/bin/protoc-gen-plugin.php \
   --grpc-php_out=build --plugin=protoc-gen-grpc-php=./vendor/bin/protoc-gen-grpc-plugin.php tests/*.proto` 

- See the [example](./example) folder for the generated code 
   
### Run test
`composer test`

# TODO
- [ ] More test case
- [ ] Pack to phar
- [ ] Support proto2
- [ ] Custom generator support (write your own code generators by require this package)

# Why do I need this instead of Google's default implementation?
- Sometimes you wish to customize the generated code,
which is complicated to do if you modify google/protobuf source code(c++) and recompile the protoc binary.
Think about above situations:
    - Add convenience methods in message class..
    - Support proto2
    - Generate custom rpc code (if you are not using gRpc or you want it to use in PHP server side) 
- Some bug in Google's generated code.
```
    public function setEnum($var)
    {
        // GPBUtil::checkEnum accepts only on param
        GPBUtil::checkEnum($var, \Gary\Test\Foo_Enum::class); 
        $this->enum = $var;
        
        return $this;
    }
```
- Cool to generate PHP code using PHP (easy for PHP developers to join).

# How does it work
That's a secret. Find out yourself.
