<?php

declare(strict_types=1);

namespace MichaelRubel\LoopFunctions\Traits;

use Illuminate\Database\Eloquent\Model;

trait WithLoopFunctions
{
    /**
     * Maps your model attributes to local class properties.
     *
     * @param Model|null $model
     * @param mixed      $rescue
     *
     * @return void
     */
    public function attributesToProperties(?Model $model = null, mixed $rescue = null): void
    {
        if (! is_null($model)) {
            $toIgnore = config('loop-functions.ignore_attributes');

            $ignores = is_array($toIgnore)
                ? $toIgnore
                : ['id', 'password'];

            collect($model->getAttributes())
                ->except($ignores)
                ->each(function ($value, $property) use ($model, $rescue) {
                    if (property_exists($this, $property)) {
                        rescue(
                            fn () => $this->{$property} = $model->{$property},
                            $rescue,
                            config('loop-functions.log') ?? false
                        );
                    }
                });
        }
    }

    /**
     * Map array data to class properties.
     *
     * @param array|null $data
     * @param mixed|null $rescue
     *
     * @return void
     */
    public function arrayToProperties(?array $data, mixed $rescue = null): void
    {
        collect($data ?? [])->each(function ($value, $key) use ($rescue) {
            if (property_exists($this, $key)) {
                rescue(
                    fn () => $this->{$key} = $value,
                    $rescue,
                    config('loop-functions.log') ?? false
                );
            }
        });
    }
}
