<?php

declare(strict_types=1);

namespace MichaelRubel\ModelMapper\Traits;

use Illuminate\Database\Eloquent\Model;

trait WithModelMapping
{
    /**
     * Maps your model attributes to local class properties.
     *
     * @param Model|null    $model
     * @param \Closure|null $failure
     *
     * @return void
     */
    public function mapModelAttributes(?Model $model = null, ?\Closure $failure = null): void
    {
        if (! is_null($model)) {
            $toIgnore = config('model-mapper.ignore_attributes');

            $ignores = is_array($toIgnore)
                ? $toIgnore
                : ['id', 'password'];

            collect($model->getAttributes())
                ->except($ignores)
                ->each(function ($value, $property) use ($model, $failure) {
                    if (property_exists($this, $property)) {
                        rescue(
                            fn () => $this->{$property} = $model->{$property},
                            $failure,
                            config('model-mapper.log') ?? false
                        );
                    }
                });
        }
    }
}
