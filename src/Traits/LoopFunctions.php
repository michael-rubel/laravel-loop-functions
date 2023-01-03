<?php

declare(strict_types=1);

namespace MichaelRubel\LoopFunctions\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait LoopFunctions
{
    use HelpsLoopFunctions;

    /**
     * Choose proper strategy to loop over the data.
     *
     * @param  Model|\ArrayAccess|array|null  $data
     * @param  mixed|null  $rescue
     * @return void
     */
    public function propertiesFrom(Model|\ArrayAccess|array|null $data = null, mixed $rescue = null): void
    {
        if ($data) {
            match (true) {
                $data instanceof Model => $this->attributesToProperties($data, $rescue),
                default => $this->arrayToProperties($data, $rescue),
            };
        }
    }

    /**
     * Maps your model attributes to local class properties.
     *
     * @param  Model|null  $model
     * @param  mixed  $rescue
     * @return void
     */
    public function attributesToProperties(?Model $model = null, mixed $rescue = null): void
    {
        collect($model?->getAttributes())
            ->except($this->ignoredPropertyNames())
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
     * @param  array|\ArrayAccess|null  $data
     * @param  mixed|null  $rescue
     * @return void
     */
    public function arrayToProperties(array|\ArrayAccess|null $data, mixed $rescue = null): void
    {
        collect($data ?? [])
            ->except($this->ignoredPropertyNames())
            ->each(function ($value, $key) use ($rescue) {
                $this->assignValue($key, $value, $rescue);

                if ($this->canWalkRecursively($value)) {
                    $this->propertiesFrom($value, $rescue);
                }
            });
    }

    /**
     * Dump class properties as key-valued array.
     *
     * @param  string|object|null  $class
     * @param  int|null  $filter
     * @param  bool  $asCollection
     * @return array|Collection
     *
     * @throws \ReflectionException
     */
    public function dumpProperties(
        string|object|null $class = null,
        bool $asCollection = false,
        ?int $filter = null,
    ): array|Collection {
        $class = match (true) {
            is_string($class) => app($class),
            is_object($class) => $class,
            default => $this,
        };

        $properties = (new \ReflectionClass($class))
            ->getProperties($filter);

        $collection = collect($properties)->mapWithKeys(
            function (\ReflectionProperty $property) use ($class) {
                $property->setAccessible(true);

                return [$property->getName() => $property->getValue($class)];
            }
        );

        return ! $asCollection
            ? $collection->all()
            : $collection;
    }
}
