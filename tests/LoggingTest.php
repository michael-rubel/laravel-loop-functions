<?php

namespace MichaelRubel\ModelMapper\Tests;

use Illuminate\Support\Facades\Log;
use MichaelRubel\ModelMapper\Tests\Boilerplate\TestModel;
use MichaelRubel\ModelMapper\Traits\WithModelMapping;

class LoggingTest extends TestCase
{
    use WithModelMapping;

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

        $this->mapModelAttributes($model);
    }

    /** @test */
    public function testMapperDoesntLogIfDisabled()
    {
        config(['model-mapper.log' => false]);

        Log::shouldReceive('error')->never();

        $model = new TestModel([
            'number' => false,
        ]);

        $this->mapModelAttributes($model);
    }
}
