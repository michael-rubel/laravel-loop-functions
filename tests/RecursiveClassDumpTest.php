<?php

namespace MichaelRubel\LoopFunctions\Tests;

use Illuminate\Support\Collection;
use MichaelRubel\LoopFunctions\Tests\Boilerplate\TestClass;

class RecursiveClassDumpTest extends TestCase
{
    /** @test */
    public function testCanDumpClassProperties()
    {
        $properties = app(TestClass::class)->dumpProperties();

        $this->assertTrue($properties['bool']);
        $this->assertStringContainsString('new string', $properties['string']);
        $this->assertArrayHasKey('array', $properties['array']);
        $this->assertTrue($properties['collection']->isEmpty());
        $this->assertArrayHasKey('static', $properties['static']);
        $this->assertArrayHasKey('test', $properties['union_type']);
        $this->assertArrayHasKey('test', $properties['static_union_type']);

        $this->assertNull($properties['nullable_string']);
        $this->assertNull($properties['nullable_array']);
        $this->assertNull($properties['nullable_collection']);
        $this->assertNull($properties['nullable_static_array']);
    }

    /** @test */
    public function testCanDumpPassedObjectProperties()
    {
        $properties = app(TestClass::class)->dumpProperties(Collection::class);

        $this->assertArrayHasKey('items', $properties);
        $this->assertArrayHasKey('escapeWhenCastingToString', $properties);
        $this->assertFalse($properties['escapeWhenCastingToString']);
    }

    /** @test */
    public function testCanDumpAsCollection()
    {
        $properties = app(TestClass::class)->dumpProperties(
            class: Collection::class,
            asCollection: true,
        );

        $this->assertInstanceOf(Collection::class, $properties);
    }
}
