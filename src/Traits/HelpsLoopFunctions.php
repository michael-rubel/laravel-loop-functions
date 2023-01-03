<?php

declare(strict_types=1);

namespace MichaelRubel\LoopFunctions\Traits;

trait HelpsLoopFunctions
{
    /**
     * Assign the value to the property or rescue.
     *
     * @param  int|string  $key
     * @param  mixed  $value
     * @param  mixed|null  $rescue
     * @return void
     *
     * @throws \ReflectionException
     */
    private function assignValue(int|string $key, mixed $value, mixed $rescue = null): void
    {
        if ($this->canAssignValue($key)) {
            rescue(
                fn () => $this->{$key} = $value,
                $rescue,
                config('loop-functions.log') ?? false
            );
        }
    }

    /**
     * @param  int|string  $key
     * @return bool
     *
     * @throws \ReflectionException
     */
    private function canAssignValue(int|string $key): bool
    {
        return is_string($key)
            && $this->checksPropertyExists($key)
            && (empty($this->{$key}) || $this->hasDefaultValue($key));
    }

    /**
     * @param  int|string  $key
     * @return bool
     */
    private function checksPropertyExists(int|string $key): bool
    {
        if ($this->allowsDynamicProperties()) {
            return true;
        }

        return property_exists($this, $key);
    }

    /**
     * @param  int|string  $key
     * @return bool
     *
     * @throws \ReflectionException
     */
    private function hasDefaultValue(int|string $key): bool
    {
        $default = (new \ReflectionProperty($this, $key))
            ->getDefaultValue();

        return ! empty($default);
    }

    /**
     * @param  mixed  $value
     * @return bool
     */
    private function canWalkRecursively(mixed $value): bool
    {
        return is_iterable($value);
    }

    /**
     * @return bool
     */
    private function allowsDynamicProperties(): bool
    {
        return config('loop-functions.dynamic_properties', true);
    }

    /**
     * @return array
     */
    private function ignoredPropertyNames(): array
    {
        return config('loop-functions.ignore_keys', ['id', 'password']);
    }
}
