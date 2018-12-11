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
        $this->assertEquals(0, $foo2->getInt32Field());
        $this->assertEquals(true, is_array($foo->getBoolPackedField())
            || $foo->getBoolPackedField() instanceof \Google\Protobuf\Internal\RepeatedField);
    }

    public function testMap() {
        $foo = new \Gary\Test\Foo();
        $m = new \Gary\Test\Foo_Embedded();
        $m->setF1(111);
        $foo->setMapField([123=>$m]);
        $str = $foo->serializeToString();

        $foo2 = new \Gary\Test\Foo();
        $foo2->mergeFromString($str);
        $m = $foo2->getMapField();
        foreach ($m as $key => $value) {
            /** @var \Gary\Test\Foo_Embedded $value */
            $this->assertEquals($key, 123);
            $this->assertEquals($value->getF1(), 111);
        }
    }

    public function testMapString() {
        $foo = new \Gary\Test\Foo();
        $foo->setMapString([123=>"aaa"]);
        $str = $foo->serializeToString();

        $foo2 = new \Gary\Test\Foo();
        $foo2->mergeFromString($str);
        $m = $foo2->getMapString();
        foreach ($m as $key => $value) {
            $this->assertEquals($key, 123);
            $this->assertEquals($value, "aaa");
        }
    }
}