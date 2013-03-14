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
        $tempPath = dirname(__FILE__) . '/../../../../temp/' ;
        $this->cacheDir = $tempPath . "fsi_cache_". uniqid();
    }

    protected function _getCacheDriver()
    {
        for ($i = 0; $i <= 32; $i ++) {
            $this->assertFalse(is_dir($this->cacheDir));
            $cache = new FileCache(array('directory' => $this->cacheDir, 'dirlevel' => $i));
            $this->assertTrue(is_dir($this->cacheDir));
            $this->rrmdir($this->cacheDir);
        }

        $this->assertFalse(is_dir($this->cacheDir));
        $cache = new FileCache(array('directory' => $this->cacheDir));
        $this->assertTrue(is_dir($this->cacheDir));
        $cache->clear();
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

    public function testRemoveItem()
    {
        $cache = $this->_getCacheDriver();
        
        $cache->setItem('key', 'test', 0, 'ns');
        $cache->setItem('key', 'test');
        
        $this->assertTrue($cache->hasItem('key'));
        $this->assertTrue($cache->hasItem('key', 'ns'));
        $this->assertTrue($cache->removeItem('key'));
        $this->assertFalse($cache->removeItem('key'));
        $this->assertTrue($cache->removeItem('key', 'ns'));
        $this->assertFalse($cache->removeItem('key', 'ns'));
        $this->assertFalse($cache->hasItem('key'));
        $this->assertFalse($cache->hasItem('key', 'ns'));
    }

    public function tearDown()
    {
        /* Clear empty cache dirs */
        $this->rrmdir($this->cacheDir);
    }

    private function rrmdir($dir)
    {
        foreach(glob($dir . '/*') as $file) {
            if(is_dir($file)) {
                $this->rrmdir($file);
            } else {
                unlink($file);
            }
        }

        rmdir($dir);
    }
}