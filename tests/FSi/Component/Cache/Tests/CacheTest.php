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


abstract class CacheTest extends \PHPUnit_Framework_TestCase
{
    public function testBasics()
    {
        $cache = $this->_getCacheDriver();

        //add item to cache
        $cache->setItem('test_cache_key', 'testing fsi cache components');

        //test if value exists in cache
        $this->assertTrue($cache->hasItem('test_cache_key'));

        //test if add item will return false if element exists in cache
        $this->assertFalse($cache->addItem('test_cache_key', 'this value should not be saved'));

        // remove item from cache
        $cache->removeItem('test_cache_key');

        // test if item was removed from cache
        $this->assertFalse($cache->hasItem('test_cache_key'));
    }

    public function testNamespace()
    {
        $cache = $this->_getCacheDriver();
        $cache->setNamespace('testnamespace');
        $cache->setItem('key1', 'test');
        $this->assertTrue($cache->hasItem('key1'));

        $cache->setNamespace('testnamespace1');
        $this->assertFalse($cache->hasItem('key1'));

        $cache->setItem('key2', 'test');
        $this->assertTrue($cache->hasItem('key2'));
    }

    public function testClearCache()
    {
        $cache = $this->_getCacheDriver();

        $cache->setNamespace('testnamespace');
        $this->assertEquals('testnamespace', $cache->getNamespace());
        $cache->setItem('key', 'test');

        $cache->setNamespace('testnamespace1');
        $this->assertEquals('testnamespace1', $cache->getNamespace());
        $cache->setItem('key1', 'test');

        $cache->clear();

        $cache->setNamespace('testnamespace');
        $this->assertFalse($cache->hasItem('key'));

        $cache->setNamespace('testnamespace1');
        $this->assertFalse($cache->hasItem('key1'));
    }
    
    public function testClearNamespaceCache()
    {
        $cache = $this->_getCacheDriver();

        $cache->setNamespace('testnamespace');
        $cache->setItem('key', 'test');
        $this->assertTrue($cache->hasItem('key'));

        $cache->setNamespace('testnamespace1');
        $cache->setItem('key1', 'test');  
        $this->assertTrue($cache->hasItem('key1'));

        $cache->clearNamespace('testnamespace');

        $cache->setNamespace('testnamespace1');
        $this->assertTrue($cache->hasItem('key1'));

        $cache->setNamespace('testnamespace');
        $this->assertFalse($cache->hasItem('key'));
    }

    public function testLifeTime()
    {
       $cache = $this->_getCacheDriver();
       $cache->setItem('key', 'test', 20);
       $cache->setItem('key1', 'test', 3);
       
       sleep(7);
       
       $this->assertTrue($cache->hasItem('key'));
       $this->assertFalse($cache->hasItem('key1'));
    }

    abstract protected function _getCacheDriver();
}