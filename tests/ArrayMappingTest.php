<?php

namespace MichaelRubel\LoopFunctions\Tests;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use MichaelRubel\LoopFunctions\Traits\LoopFunctions;

class ArrayMappingTest extends TestCase
{
    use LoopFunctions;

    public bool $test = false;

    public ?string $name = null;

    public ?string $password = null;

    public ?array $additional_data = [];

    public ?array $array = [];

    public ?Collection $supportCollection = null;

    public ?EloquentCollection $eloquentCollection = null;

    public function setUp(): void
    {
        parent::setUp();

        unset($this->test);
        unset($this->name);
        unset($this->additional_data);
        unset($this->array);
        unset($this->supportCollection);
        unset($this->eloquentCollection);
    }

    /** @test */
    public function testCanMapAnArrayToProperties()
    {
        $array = [
            'test' => true,
            'name' => 'Michael Rubel',
            'password' => 'p@$$w0rd',
        ];

        $this->arrayToProperties($array);

        $this->assertTrue($this->test);
        $this->assertStringContainsString('Michael', $this->name);
        $this->assertNull($this->password);
    }

    /** @test */
    public function testCanMapAnArray()
    {
        $array = [
            'test' => true,
            'name' => 'Michael Rubel',
            'password' => 'p@$$w0rd',
            'additional_data' => [
                'next' => 'test',
            ],
        ];

        $this->arrayToProperties($array);

        $this->assertTrue($this->test);
        $this->assertStringContainsString('Michael', $this->name);
        $this->assertNull($this->password);
        $this->assertArrayHasKey('next', $this->additional_data);
    }

    /** @test */
    public function testAlreadyInitializedPropertiesArentOverridenByNestedArrays()
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

    /** @test */
    public function testCanMapUsingPropertiesFrom()
    {
        $array = [
            'test' => true,
            'additional_data' => [
                'next' => [
                    'test' => true,
                ],
            ],
        ];

        $this->propertiesFrom($array);

        $this->assertArrayHasKey('next', $this->additional_data);

        $this->propertiesFrom(null);

        $this->assertArrayHasKey('next', $this->additional_data);
    }
}
