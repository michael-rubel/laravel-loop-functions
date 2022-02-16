<?php

namespace MichaelRubel\LoopFunctions\Tests;

use MichaelRubel\LoopFunctions\Traits\WithLoopFunctions;

class ArrayMappingTest extends TestCase
{
    use WithLoopFunctions;

    public bool $test;
    public string $name;
    public ?string $password = null;

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
}
