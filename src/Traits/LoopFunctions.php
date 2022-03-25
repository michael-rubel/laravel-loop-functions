<?php

declare(strict_types=1);

namespace MichaelRubel\LoopFunctions\Traits;

use Illuminate\Database\Eloquent\Model;

trait LoopFunctions
{
    use HelpsLoopFunctions;

    /**
     * Choose proper strategy to loop over the data.
     *
     * @param Model|array|null $data
     * @param mixed|null       $rescue
     *
     * @return void
     */
    public function propertiesFrom(Model|array|null $data = null, mixed $rescue = null): void
    {
        if ($data) {
            match (true) {
                is_array($data)        => $this->arrayToProperties($data, $rescue),
                $data instanceof Model => $this->attributesToProperties($data, $rescue),
            };
        }
    }

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
        collect($model?->getAttributes())
            ->except($this->ignoreKeys())
            ->each(
                fn ($value, $property) => $this->assignValue(
                    $property,
                    $model->{$property},
                    $rescue
                )
            );
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
                if ($this->canWalkRecursively($value, $key)) {
                    $this->arrayToProperties($value, $rescue);
                }

                $this->assignValue($key, $value, $rescue);
            });
    }
}
