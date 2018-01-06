<?php
/**
 * Auto generated from tests/test3.proto at 2018-01-06 10:36:20
 *
 * package: gary.test
 */

namespace Gary\Test;

/**
 * Gary\Test\Foo_Enum enum embedded in .gary.test.Foo message
 */
final class Foo_Enum
{
    const EnumOne = 0;
    
    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'EnumOne' => self::EnumOne,
        );
    }
}
