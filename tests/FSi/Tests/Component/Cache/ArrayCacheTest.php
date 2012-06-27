<?php

namespace FSi\Tests\Component\Cache;

use FSi\Component\Cache\ArrayCache;

class ArrayCacheTest extends CacheTest
{
    protected function _getCacheDriver()
    {
        return new ArrayCache();
    }
}