<?php

namespace MichaelRubel\ModelMapper\Tests;

use MichaelRubel\ModelMapper\Traits\WithModelMapping;

class ArrayMappingTest extends TestCase
{
    use WithModelMapping;

    public bool $test;
    public string $name;
    public string $password;

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
        $this->assertStringContainsString('$$', $this->password);
    }
}
