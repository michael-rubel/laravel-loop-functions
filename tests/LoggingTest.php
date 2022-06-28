<?php

namespace MichaelRubel\LoopFunctions\Tests;

use Illuminate\Support\Facades\Log;
use MichaelRubel\LoopFunctions\Tests\Boilerplate\TestModel;
use MichaelRubel\LoopFunctions\Traits\LoopFunctions;

class LoggingTest extends TestCase
{
    use LoopFunctions;

    public int $number;

    /** @test */
    public function testMapperCanLog()
    {
        Log::shouldReceive('error')
            ->once()
            ->withArgs(function ($message) {
                return str_contains($message, 'Cannot assign');
            });

        $model = new TestModel([
            'number' => false,
        ]);

        $this->attributesToProperties($model);
    }

    /** @test */
    public function testMapperDoesntLogIfDisabled()
    {
        config(['loop-functions.log' => false]);

        Log::shouldReceive('error')->never();

        $model = new TestModel([
            'number' => false,
        ]);

        $this->attributesToProperties($model);
    }
}
