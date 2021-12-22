<?php

declare(strict_types=1);

namespace MichaelRubel\ModelMapper\Traits;

use Illuminate\Database\Eloquent\Model;

trait WithModelMapping
{
    /**
     * Maps your model attributes to local class properties.
     *
     * @param Model $model
     *
     * @return void
     */
    public function mapModelAttributes(Model $model): void
    {
        collect($model->getAttributes())->each(function ($value, $property) {
            if (property_exists($this, $property)) {
                rescue(fn () => $this->{$property} = $value);
            }
        });
    }
}
