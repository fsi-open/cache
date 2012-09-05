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

use FSi\Component\Cache\FileCache;

class FileCacheTest extends CacheTest
{
    protected $cacheDir;
    
    protected function setUp()
    {
        /* Setup unique cache dir */
        $this->cacheDir = sys_get_temp_dir() . "fsi_cache_". uniqid();
    }
    
    protected function _getCacheDriver()
    {
        $this->assertFalse(is_dir($this->cacheDir));
        $cache = new FileCache($this->cacheDir, 3);
        $this->assertTrue(is_dir($this->cacheDir));

        return $cache;
    }
    
    public function testClearExpired()
    {
       $cache = $this->_getCacheDriver();
       $cache->setItem('key1', 'test', 20);
       $cache->setItem('key2', 'test', 3);
       
       sleep(5);
       $cache->clearExpired();
       
       $this->assertTrue($cache->hasItem('key1'));
       $this->assertFalse($cache->hasItem('key2'));
    }
    
    public function tearDown()
    {
        /* Clear empty cache dirs */
        $this->rrmdir($this->cacheDir);
    }
    
    private function rrmdir($dir) {
        foreach(glob($dir . '/*') as $file) {
            if(is_dir($file))
                $this->rrmdir($file);
            else
                unlink($file);
        }
        rmdir($dir);
    }
}