<?php

declare(strict_types=1);

namespace MichaelRubel\LoopFunctions\Traits;

use Illuminate\Database\Eloquent\Model;

trait LoopFunctions
{
    use HelpsLoopFunctions;

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
            collect($model->getAttributes())
                ->except($this->ignoreKeys())
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
        collect($data ?? [])
            ->except($this->ignoreKeys())
            ->each(function ($value, $key) use ($rescue) {
                if ($this->canWalkRecursively($value)) {
                    $this->arrayToProperties($value, $rescue);
                }

                $this->assignValue($key, $value, $rescue);
            });
    }
}
