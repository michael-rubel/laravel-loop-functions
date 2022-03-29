<?php

namespace MichaelRubel\LoopFunctions\Tests\Boilerplate;

use Illuminate\Support\Collection;
use MichaelRubel\LoopFunctions\Traits\LoopFunctions;

class TestClass
{
    use LoopFunctions;

    public bool $bool = false;
    public string $string = 'string';
    public ?string $nullable_string = 'string';
    public array $array = [];
    public ?array $nullable_array = [];

    protected Collection $collection;
    protected ?Collection $nullable_collection = null;

    private static array $static = [];
    private static ?array $nullable_static_array = null;

    public function __construct()
    {
        $this->bool = true;
        $this->string = 'new string';
        $this->nullable_string = null;
        $this->array = ['array' => true];
        $this->nullable_array = null;
        $this->collection = new Collection;
        $this->nullable_collection = null;
        self::$static = ['static' => true];
        self::$nullable_static_array = null;
    }
}
