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
- clone the code cd cd to the directory
- `composer install`
- `./protoc-gen.php --out=example --grpc_out=example tests/*.proto` or use directly as plugin:

   `protoc --php-custom_out=build --plugin=protoc-gen-php-custom=protoc-gen-plugin.php --custom-grpc_out=build --plugin=protoc-gen-custom-grpc=protoc-gen-grpc-plugin.php tests/*.proto` 
### Run test
`composer test`

# TODO
- [ ] More test case
- [ ] Pack to phar
- [ ] Add to packagist
- [ ] Support proto2
- [ ] Add License
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
