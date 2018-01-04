# Introduction 
Generate php protobuf code using php
`./protoc-gen-php -o build tests/test3.proto`

# Requirements
- Unix\Linux system
- php >= 5.3 and composer
- Only support proto3 syntax proto file
- protoc installed, version >= 3.5

# Usage
- clone the code cd cd to the directory
- `composer install`
- `patch -p0 google-protobuf.patch` # patch the official protobuf library...
- `./protoc-gen-php -o build tests/test3.proto`

### Run test
`./test.sh`

# TODO
- [ ] More test case
- [ ] gRPC service code generation
- [ ] Pack to phar
- [ ] Add to packagist
- [ ] Support proto2
- [ ] Add License


# How does it work
That's a secret. Find out yourself.
