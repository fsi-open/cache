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

    /**
     * {@inheritdoc}
     */
    public function getItem($key, $namespace = null)
    {
        $curretnNamespace = $this->buildNamespace($namespace);
        if (!isset(self::$cache[$curretnNamespace][$key])) {
            return false;
        }

        return self::$cache[$curretnNamespace][$key];
    }

    /**
     * {@inheritdoc}
     */
    public function addItem($key, $item, $lifetime = 0, $namespace = null)
    {
        if ($this->hasItem($key)) {
            return false;
        }

        $curretnNamespace = $this->buildNamespace($namespace);

        self::$cache[$curretnNamespace][$key] = $item;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function setItem($key, $item, $lifetime = 0, $namespace = null)
    {
        $curretnNamespace = $this->buildNamespace($namespace);
        self::$cache[$curretnNamespace][$key] = $item;
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem($key, $namespace = null)
    {
        $curretnNamespace = $this->buildNamespace($namespace);
        if (!isset(self::$cache[$curretnNamespace][$key])) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem($key, $namespace = null)
    {
        if (!$this->hasItem($key)) {
            return false;
        }

        $curretnNamespace = $this->buildNamespace($namespace);
        unset(self::$cache[$curretnNamespace][$key]);
        return true;
    }

    public function clear()
    {
        foreach (self::$cache as $namespace => $storage) {
            self::$cache[$namespace] = array();
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

    private function buildNamespace($namespace = null)
    {
        $curretnNamespace = (isset($namespace)) ? $namespace : $this->getNamespace();
        if (!array_key_exists($curretnNamespace, self::$cache)) {
            self::$cache[$curretnNamespace] = array();
        }

        return $curretnNamespace;
    }
}
