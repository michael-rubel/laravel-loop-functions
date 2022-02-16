<?php

namespace MichaelRubel\LoopFunctions\Tests;

use MichaelRubel\LoopFunctions\Traits\LoopFunctions;

class ArrayMappingTest extends TestCase
{
    use LoopFunctions;

    public bool $test;
    public string $name;
    public ?string $password = null;
    public string $next;

    /** @test */
    public function testCanMapAnArrayToProperties()
    {
        $array = [
            'test'     => true,
            'name'     => 'Michael Rubel',
            'password' => 'p@$$w0rd',
        ];

        $this->arrayToProperties($array);

        $this->assertTrue($this->test);
        $this->assertStringContainsString('Michael', $this->name);
        $this->assertNull($this->password);
    }

    /** @test */
    public function testCanMapAnArrayRecursively()
    {
        $array = [
            'test'     => true,
            'name'     => 'Michael Rubel',
            'password' => 'p@$$w0rd',
            'additional_data' => [
                'next' => 'test',
            ],
        ];

        $this->arrayToProperties($array);

        $this->assertTrue($this->test);
        $this->assertStringContainsString('Michael', $this->name);
        $this->assertNull($this->password);
        $this->assertStringContainsString('test', $this->next);
    }

    /** @test */
    public function testAlreadyInitializedPropertiesArentOverridesByNestedArrays()
    {
        $array = [
            'name' => 'Michael Rubel',
            'additional_data' => [
                'name' => 'test',
            ],
        ];

        $this->arrayToProperties($array);

        $this->assertStringContainsString('Michael', $this->name);
    }
}
