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

class ArrayCache extends AbstractCache
{
    static private $cache = array();
    
    /**
     * Build static $cache array that will simulate normal cache storage.
     * 
     * @param unknown_type $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        self::$cache[$this->getNamespace()] = array();
    }
    
    public function setNamespace($namespace)
    {
        parent::setNamespace($namespace);
        if (!isset(self::$cache[$this->getNamespace()]))
            self::$cache[$this->getNamespace()] = array();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getItem($key)
    {
        if (!isset(self::$cache[$this->getNamespace()][$key]))
            return false;
        
        return self::$cache[$this->getNamespace()][$key];
    }
    
    /**
     * {@inheritdoc}
     */
    public function addItem($key, $item, $lifetime = 0)
    {
        if ($this->hasItem($key))
            return false;
            
        self::$cache[$this->getNamespace()][$key] = $item;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function setItem($key, $item, $lifetime = 0)
    {
        self::$cache[$this->getNamespace()][$key] = $item;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem($key)
    {
        if (!isset(self::$cache[$this->getNamespace()][$key]))
            return false;
        
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeItem($key)
    {
        if (!$this->hasItem($key))
            return false;
            
        unset(self::$cache[$this->getNamespace()][$key]);
        return true;
    }

    public function clear()
    {
        foreach (self::$cache as $key => $namespace) {
            self::$cache[$key] = array();
        }
        return true;
    }
    
    public function clearNamespace($namespace)
    {
        if (isset(self::$cache[$namespace])) {
            self::$cache[$namespace] = array();
        }
        return true;
    }
}
