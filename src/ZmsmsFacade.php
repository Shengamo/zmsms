<?php

namespace Shengamo\Zmsms;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Shengamo\Zmsms\Skeleton\SkeletonClass
 */
class ZmsmsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'zmsms';
    }
}
