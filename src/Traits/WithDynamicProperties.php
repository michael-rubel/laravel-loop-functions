<?php

namespace MichaelRubel\LoopFunctions\Traits;

trait WithDynamicProperties
{
    /**
     * @param  string  $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return $this->{$name};
    }

    /**
     * @param  string  $name
     * @param  mixed  $value
     * @return void
     */
    public function __set(string $name, mixed $value): void
    {
        $this->{$name} = $value;
    }
}
