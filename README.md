# Introduction 
Generate php protobuf code using php
`./protoc-gen-php -o build tests/test3.proto`

The generated code is meant to work with protobuf's official php implementation:
https://github.com/google/protobuf/tree/master/php 

# Requirements
- Unix/Linux system
- php >= 7.0 and composer installed
- Only support proto3 syntax proto file
- protoc installed, version >= 3.5

# Usage
- clone the code cd cd to the directory
- `composer install`
- `./protoc-gen-php -o build tests/test3.proto`

### Run test
`composer test`

# TODO
- [ ] More test case
- [ ] gRPC service code generation
- [ ] Pack to phar
- [ ] Add to packagist
- [ ] Support proto2
- [ ] Add License


# How does it work
That's a secret. Find out yourself.
