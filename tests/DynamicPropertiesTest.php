<?php

namespace MichaelRubel\LoopFunctions\Tests;

use Illuminate\Support\Collection;
use MichaelRubel\LoopFunctions\Tests\Boilerplate\TestClass;
use MichaelRubel\LoopFunctions\Traits\LoopFunctions;

class DynamicPropertiesTest extends TestCase
{
    use LoopFunctions;

    public function __get(string $name): mixed
    {
        return $this->{$name};
    }

    public function __set(string $name, $value): void
    {
        $this->{$name} = $value;
    }

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
