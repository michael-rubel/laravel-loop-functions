<?php

namespace MichaelRubel\LoopFunctions\Tests;

use Illuminate\Support\Collection;
use MichaelRubel\LoopFunctions\Traits\LoopFunctions;

class RecursiveArrayMappingTest extends TestCase
{
    use LoopFunctions;

    public bool $test = false;

    public ?string $nice = 'no';

    public ?string $first_name = null;

    public ?string $last_name = null;

    public ?string $full_name = null;

    public ?array $array = null;

    public ?Collection $collection = null;

    /** @test */
    public function testCanMapRecursively()
    {
        $data = [
            'collection' => collect([
                'first_name' => 'Michael',
                'last_name' => 'Rubel',
            ]),
            'last_name' => 'Rubel',
            'array' => [
                'test' => true,
                'full_name' => 'Michael Rubel',
                'first_name' => 'Tester',
                'last_name' => 'Field',
                'nice' => 'yes',
            ],
            'nice' => 'yes',
        ];

        $this->propertiesFrom($data);

        $this->assertTrue($this->test);
        $this->assertArrayHasKey('test', $this->array);
        $this->assertArrayHasKey('first_name', $this->collection);
        $this->assertStringContainsString('Michael', $this->first_name);
        $this->assertStringContainsString('Rubel', $this->last_name);
        $this->assertStringContainsString('Michael Rubel', $this->full_name);
        $this->assertStringContainsString('yes', $this->nice);
    }
}
