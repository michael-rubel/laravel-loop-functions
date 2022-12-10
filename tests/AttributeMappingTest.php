<?php

namespace MichaelRubel\LoopFunctions\Tests;

use Illuminate\Support\Collection;
use MichaelRubel\LoopFunctions\Tests\Boilerplate\TestModel;
use MichaelRubel\LoopFunctions\Traits\LoopFunctions;

class AttributeMappingTest extends TestCase
{
    use LoopFunctions;

    public int $id;

    public bool $test;

    public string $name;

    public string $password;

    public object $files;

    public ?\Closure   $default = null;

    public ?Collection $collection = null;

    public ?string     $intAsString = null;

    /** @test */
    public function testMapsAttributesToClassPropertiesCorrectly()
    {
        $model = new TestModel([
            'id' => 1,
            'test' => true,
            'name' => 'mapped',
            'files' => collect('/img/src/screen.png'),
        ]);

        $this->attributesToProperties($model);

        $this->assertTrue($this->test);
        $this->assertIsString($this->name);
        $this->assertStringContainsString('mapped', $this->name);
        $this->assertInstanceOf(Collection::class, $this->files);
    }

    /** @test */
    public function testMappingIgnoresDifferentTypes()
    {
        $model = new TestModel([
            'id' => 1,
            'test' => true,
            'name' => 100.1,
            'files' => null,
            'default' => fn () => true,
        ]);

        $this->attributesToProperties($model);

        $this->assertFalse(isset($this->name));
        $this->assertFalse(isset($this->files));

        $this->assertInstanceOf(\Closure::class, $this->default);
        $this->assertTrue(($this->default)());
    }

    /** @test */
    public function testMappingWorksWithCollections()
    {
        $model = new TestModel([
            'id' => 1,
            'collection' => new Collection(),
        ]);

        $this->attributesToProperties($model);

        $this->assertInstanceOf(Collection::class, $this->collection);
    }

    /** @test */
    public function testMappingWithCasts()
    {
        $model = new TestModel([
            'collection' => [
                0 => true,
                1 => false,
            ],
            'intAsString' => 100,
        ]);

        $this->attributesToProperties($model);

        $this->assertInstanceOf(Collection::class, $this->collection);
        $this->assertIsString($this->intAsString);
    }

    /** @test */
    public function testIdAndPasswordIsIgnored()
    {
        $model = new TestModel([
            'id' => 1,
            'password' => 'hash',
            'test' => true,
        ]);

        $this->attributesToProperties($model);

        $this->assertTrue($this->test);
        $this->assertFalse((new \ReflectionProperty($this, 'id'))->isInitialized($this));
        $this->assertFalse((new \ReflectionProperty($this, 'password'))->isInitialized($this));
    }

    /** @test */
    public function testSetsDefaultValueWithWrongConfig()
    {
        config(['loop-functions.ignore_attributes' => 123]);

        $model = new TestModel([
            'id' => 1,
            'password' => 'hash',
            'test' => true,
        ]);

        $this->attributesToProperties($model);

        $this->assertTrue($this->test);
        $this->assertFalse((new \ReflectionProperty($this, 'id'))->isInitialized($this));
        $this->assertFalse((new \ReflectionProperty($this, 'password'))->isInitialized($this));
    }

    /** @test */
    public function testCanMapUsingPropertiesFrom()
    {
        $model = new TestModel([
            'collection' => [
                0 => true,
                1 => false,
            ],
            'intAsString' => 100,
        ]);

        $this->propertiesFrom($model);

        $this->assertInstanceOf(Collection::class, $this->collection);
        $this->assertIsString($this->intAsString);

        $this->propertiesFrom(null);

        $this->assertInstanceOf(Collection::class, $this->collection);
        $this->assertIsString($this->intAsString);
    }
}
