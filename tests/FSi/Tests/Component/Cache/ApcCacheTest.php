<?php

namespace FSi\Tests\Component\Cache;

use FSi\Component\Cache\ApcCache;

class ApcCacheTest extends CacheTest
{
    public function setUp()
    {
        if ( ! extension_loaded('apc') || false === @apc_cache_info()) {
            $this->markTestSkipped('The ' . __CLASS__ .' requires the use of APC');
        }
    }
    
    protected function _getCacheDriver()
    {
        return new ApcCache();
    }
}