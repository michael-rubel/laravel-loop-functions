<?php

declare(strict_types=1);

namespace MichaelRubel\LoopFunctions\Traits;

trait HelpsLoopFunctions
{
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
        if ($this->canAssignValue($key)) {
            rescue(
                fn () => $this->{$key} = $value,
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

    /**
     * Check if the function can walk recursively over an array.
     *
     * @param mixed      $value
     * @param int|string $key
     *
     * @return bool
     */
    private function canWalkRecursively(mixed $value, int|string $key): bool
    {
        return is_array($value) && ! empty($this->{$key});
    }

    /**
     * @param int|string $key
     *
     * @return bool
     */
    private function canAssignValue(int|string $key): bool
    {
        return is_string($key) && property_exists($this, $key);
    }
}
