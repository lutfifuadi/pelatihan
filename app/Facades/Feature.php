<?php

namespace App\Facades;

use App\Services\FeatureManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool isOn(string $key)
 * @method static bool isOff(string $key)
 * @method static void guard(string $key)
 * @method static array list()
 * @method static array listGrouped()
 * @method static void set(string $key, bool $value)
 *
 * @see \App\Services\FeatureManager
 */
class Feature extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return FeatureManager::class;
    }
}
