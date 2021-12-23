<?php

declare(strict_types=1);

namespace MichaelRubel\ModelMapper\Traits;

use Illuminate\Database\Eloquent\Model;

trait WithModelMapping
{
    /**
     * Maps your model attributes to local class properties.
     *
     * @param Model|null $model
     *
     * @return void
     */
    public function mapModelAttributes(?Model $model = null): void
    {
        if (! is_null($model)) {
            collect($model->getAttributes())->each(function ($value, $property) use ($model) {
                if (property_exists($this, $property)) {
                    rescue(
                        fn () => $this->{$property} = $model->{$property},
                        fn () => null,
                        config('model-mapper.log') ?? false
                    );
                }
            });
        }
    }
}
