<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Cache\Tests;

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

    public function testLifeTime()
    {
        $this->markTestSkipped('APC cache does not implement lifetime.');
    }
}