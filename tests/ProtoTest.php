<?php

/**
 * Created by PhpStorm.
 * User: liugang
 * Date: 2018/1/5
 * Time: 下午5:56
 */
class ProtoTest extends \PHPUnit\Framework\TestCase
{
    public function testFoo()
    {
        $foo = new \Gary\Test\Foo();
        $foo->setBoolField(true);
        $input = "hello world";
        $foo->setStringField($input);
        $str = $foo->serializeToString();

        $foo2 = new \Gary\Test\Foo();
        $foo2->mergeFromString($str);
        $this->assertEquals($foo2->getStringField(), $input);
    }
}