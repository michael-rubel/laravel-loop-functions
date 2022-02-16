<?php

namespace MichaelRubel\LoopFunctions\Tests;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use MichaelRubel\LoopFunctions\Traits\LoopFunctions;

class ArrayMappingTest extends TestCase
{
    use LoopFunctions;

    public bool $test;
    public string $name;
    public ?string $password = null;
    public string $next;
    public array $array = [];
    public ?Collection $supportCollection = null;
    public ?EloquentCollection $eloquentCollection = null;

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

    /** @test */
    public function testArrayIsAssignedAsIs()
    {
        $array = [
            'name' => 'Michael Rubel',
            'array' => [
                'test' => true,
            ],
        ];

        $this->arrayToProperties($array);

        $this->assertStringContainsString('Michael', $this->name);
        $this->assertArrayHasKey('test', $this->array);
        $this->assertTrue($this->array['test']);
    }

    /** @test */
    public function testCollectionAssignmentIsOk()
    {
        $array = [
            'supportCollection' => collect([
                'test' => true,
            ]),
            'eloquentCollection' => new EloquentCollection(),
        ];

        $this->arrayToProperties($array);

        $this->assertArrayHasKey('test', $this->supportCollection->toArray());
        $this->assertTrue($this->supportCollection->toArray()['test']);
    }
}
