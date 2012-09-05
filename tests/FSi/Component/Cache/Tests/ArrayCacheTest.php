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

use FSi\Component\Cache\ArrayCache;

class ArrayCacheTest extends CacheTest
{
    protected function _getCacheDriver()
    {
        return new ArrayCache();
    }
    
    public function testLifeTime()
    {
        $this->markTestSkipped('Array cache does not implement lifetime.');
    }
}