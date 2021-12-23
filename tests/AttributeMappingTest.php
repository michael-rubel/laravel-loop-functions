<?php

namespace MichaelRubel\ModelMapper\Tests;

use Illuminate\Support\Collection;
use MichaelRubel\ModelMapper\Tests\Boilerplate\TestModel;
use MichaelRubel\ModelMapper\Traits\WithModelMapping;

class AttributeMappingTest extends TestCase
{
    use WithModelMapping;

    public int $id;
    public bool $test;
    public string $name;
    public object $files;
    public ?\Closure $default = null;
    public ?Collection $collection = null;

    /** @test */
    public function testMapsAttributesToClassPropertiesCorrectly()
    {
        $model = new TestModel([
            'id'    => 1,
            'test'  => true,
            'name'  => 'mapped',
            'files' => collect('/img/src/screen.png'),
        ]);

        $this->mapModelAttributes($model);

        $this->assertIsInt($this->id);
        $this->assertTrue($this->test);
        $this->assertIsString($this->name);
        $this->assertStringContainsString('mapped', $this->name);
        $this->assertInstanceOf(Collection::class, $this->files);
    }

    /** @test */
    public function testMappingIgnoresDifferentTypes()
    {
        $model = new TestModel([
            'id'      => 1,
            'test'    => true,
            'name'    => 100.1,
            'files'   => null,
            'default' => fn () => true,
        ]);

        $this->mapModelAttributes($model);

        $this->assertFalse((new \ReflectionProperty($this, 'name'))->isInitialized($this));
        $this->assertFalse((new \ReflectionProperty($this, 'files'))->isInitialized($this));

        $this->assertInstanceOf(\Closure::class, $this->default);
        $this->assertTrue(($this->default)());
    }

    /** @test */
    public function testMappingWorksWithCollections()
    {
        $model = new TestModel([
            'id'         => 1,
            'collection' => new Collection(),
        ]);

        $this->mapModelAttributes($model);

        $this->assertInstanceOf(Collection::class, $this->collection);
    }
}
