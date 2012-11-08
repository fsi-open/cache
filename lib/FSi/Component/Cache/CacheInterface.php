<?php

/*
 * This file is part of the FSi Component package.
 *
 * (c) Norbert Orzechowicz <norbert@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Cache;

interface CacheInterface
{
    /**
     * Get an item from cache storage.
     *
     * @param string $key
     * @param string $namespace
     * @return mixed|boolean mixed data on success, false if cache is emtpy.
     */
    public function getItem($key, $namespace = null);

    /**
     * Check if item exists.
     *
     * @param string $key
     */
    public function hasItem($key, $namespace = null);

    /**
     * Add item into cache, only if it's not already cached.
     *
     * @param string $key
     * @param mixed $item
     * @param integer $lifetime
     * @return boolean|null return false if value is already in cache and null on error
     */
    public function addItem($key, $item, $lifetime = 0, $namespace = null);

    /**
     * Cache item in the data store. If item is already cached it will be
     * owerwritten.
     *
     * @param string $key
     * @param mixed $item
     * @param integer $lifetime
     * @return boolean return false on error
     */
    public function setItem($key, $item, $lifetime = 0, $namespace = null);

    /**
     * Remove cached item.
     *
     * @param string $key
     * @return boolean false if item not found in cache and true if removed
     */
    public function removeItem($key, $namespace = null);

    /**
     * Clear all namespaces in cache.
     *
     * @param string $key
     * @return boolean return false if clearing cach fails
     */
    public function clear();

    /**
     * Get namespace for cache
     *
     * @return string
     */
    public function getNamespace();

    /**
     * Sets cache namespace
     *
     * @param string $namespace
     * @return CacheAdapterInterface
     */
    public function setNamespace($namespace);

    /**
     * Clear passed namespace in cache.
     *
     * @param string $key
     * @return boolean return false if clearing cach fails
     */
    public function clearNamespace($namespace);
}