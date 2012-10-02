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

abstract class AbstractCache implements CacheInterface
{
    /**
     * Cache namespace by defaul fsicache
     * @var type 
     */
    protected $namespace = 'fsicache'; 
    
    /**
     * Cache options
     * @var array 
     */
    protected $options = array();
    
    public function __construct($options = null)
    {
        if (isset($options)) {
            $this->setOptions($options);
        }
    }
    
    /**
     * Set cache options. 
     * 
     * @param array $options
     * @return 
     */
    public function setOptions($options)
    {
        if (is_array($options)) {
            $this->options = $options;
            foreach ($options as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }
    
    /**
     * Get cache options.
     * 
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function setNamespace($namespace)
    {
        $this->namespace = (string)$namespace;
        return $this;
    }
}