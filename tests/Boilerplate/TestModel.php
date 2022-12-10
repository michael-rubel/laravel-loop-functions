<?php

namespace MichaelRubel\LoopFunctions\Tests\Boilerplate;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var string[]
     */
    protected $casts = [
        'collection' => AsCollection::class,
        'intAsString' => 'string',
    ];
}
