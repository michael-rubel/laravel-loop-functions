<?php

namespace MichaelRubel\LoopFunctions\Tests;

use MichaelRubel\LoopFunctions\Traits\LoopFunctions;
use MichaelRubel\LoopFunctions\Traits\WithDynamicProperties;

class DynamicPropertiesTest extends TestCase
{
    use LoopFunctions, WithDynamicProperties;

    /** @test */
    public function testCanUseDynamicProperties()
    {
        config(['loop-functions.dynamic_properties' => true]);

        $array = [
            'test' => true,
        ];

        $this->propertiesFrom($array);

        $this->assertTrue($this->test);
    }

    /** @test */
    public function testDynamicPropertiesDisabled()
    {
        config(['loop-functions.dynamic_properties' => false]);

        $this->expectException(\ErrorException::class);

        $array = [
            'test' => true,
        ];

        $this->propertiesFrom($array);

        $this->assertFalse($this->test);
    }
}
