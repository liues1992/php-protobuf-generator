# Introduction 
Generate php protobuf code using php
`./protoc-gen.php -o build tests/test3.proto`

The generated message code is meant to work with Google protobuf's official php implementation:
https://github.com/google/protobuf/tree/master/php

The generated service client code is meant to  work with gRpc
https://grpc.io/docs/quickstart/php.html#prerequisites

# Requirements
- Unix/Linux system
- php >= 7.0 and composer installed
- Only support proto3 syntax proto file
- protoc installed, version >= 3.5

# Usage
- clone the code cd cd to the directory
- `composer install`
- `./protoc-gen.php -o example tests/*.proto`

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
which is complicated to do if you modify google/protobuf source code(c++) and recompile the protoc binary
- Cool to generate php code using php.

# How does it work
That's a secret. Find out yourself.
