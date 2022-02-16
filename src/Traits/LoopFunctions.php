<?php

declare(strict_types=1);

namespace MichaelRubel\LoopFunctions\Traits;

use Illuminate\Database\Eloquent\Model;

trait LoopFunctions
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
                $propertyType = rescue(
                    callback: fn () => (
                        new \ReflectionProperty($this, $key)
                    )->getType()->getName(),
                    report: false
                );

                if (is_array($value) && $propertyType !== 'array') {
                    $this->arrayToProperties($value, $rescue);
                }

                $this->assignValue($key, $value, $rescue);
            });
    }

    /**
     * Assign the value to the property or rescue.
     *
     * @param int|string $key
     * @param mixed      $value
     * @param mixed|null $rescue
     *
     * @return void
     */
    private function assignValue(int|string $key, mixed $value, mixed $rescue = null): void
    {
        if (property_exists($this, $key)) {
            rescue(
                fn () => ! empty($this->{$key}) ?: $this->{$key} = $value,
                $rescue,
                config('loop-functions.log') ?? false
            );
        }
    }

    /**
     * @return array
     */
    private function ignoreKeys(): array
    {
        $defaults = ['id', 'password'];

        $ignores = config('loop-functions.ignore_keys', $defaults);

        return is_array($ignores)
            ? $ignores
            : $defaults;
    }
}
