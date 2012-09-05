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

class ApcCache extends AbstractCache
{
    /**
     * Separator used to create element key from item key and namespace
     * $namespace.$namespaceSeparator.$key = Element 
     * 
     * @var string
     */
    protected $namespaceSeparator = ':'; 
    
    public function __construct($options = null)
    {
        if (version_compare('3.0.0', phpversion('apc')) > 0) {
            throw new Exception\ApcCacheException("Apc extension version must be >= 3.0.0");
        }

        $enabled = ini_get('apc.enabled');
        if (PHP_SAPI == 'cli') {
            $enabled = $enabled && (bool) ini_get('apc.enable_cli');
        }

        if (!$enabled) {
            throw new Exception\ApcCacheException(
                "Apc extension is disabled - check php.ini options 'apc.enabled' and 'apc.enable_cli'"
            );
        }
        parent::__construct($options);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getItem($key)
    {
        $success  = null;
        $item     = apc_fetch($this->buildKey($key), $success);

        if (!$success) {
            return $success;
        }
        return $item;
    }
    
    /**
     * {@inheritdoc}
     */
    public function addItem($key, $item, $lifetime = 0)
    {
        if (!apc_add($this->buildKey($key), $item, (int)$lifetime)) {
            return false;
        }
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setItem($key, $item, $lifetime = 0)
    {
        if (!apc_store($this->buildKey($key), $item, (int)$lifetime)) {
            return false;
        }
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function hasItem($key)
    {
        return apc_exists($this->buildKey($key));
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeItem($key)
    {
        if (!$this->hasItem($key))
            return false;

        return apc_delete($this->buildKey($key));
    }
    
    /**
     * Build key from namespace and namespaceSeparator;
     */
    protected function buildKey($key)
    {
        return $this->getNamespace() . $this->namespaceSeparator . $key;
    }

    public function clear()
    {
        apc_clear_cache('user');
        return true;
    }
    
    public function clearNamespace($namespace)
    {
        $cacheData = apc_cache_info('user');
        $keys = array();

        foreach ($cacheData['cache_list'] as $entry) {
            if (strpos($entry['info'], $namespace.$this->namespaceSeparator) !== false) {
                $keys[] = $entry['info'];
            }
        }

        foreach ($keys as $key) {
            apc_delete($key);
        }

        return true;
    }
}