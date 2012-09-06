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

class FileCache extends AbstractCache
{
    protected $directory;

    /**
     * Separator used to create element key from item key and namespace
     * $namespace.$namespaceSeparator.$key = Element 
     * 
     * @var string
     */
    protected $namespaceSeparator = '_'; 

    /**
     * How deep in cache dir file should be storde.
     * 
     * @var integer
     */
    protected $dirlvl = 0;

    /**
     * Required options
     * 'directory' (string) the root directory for cache. 
     * 
     * Allowed options 
     * 'dirlvl' (integer) must be lower than 32. Cache directory deep level.
     * The more items in cache the greater should be the dirlvl parameter.  
     * 
     * Example: 
     * new FileCache(array('directory' => '/tmp', 'dirlvl' => 3));
     * 
     * @param unknown_type $options
     */
    public function __construct($options = array())
    {
        if (!is_array($options)) {
            throw new \InvalidArgumentException('File Cache require options as an array.');
        }
        
        if (!array_key_exists('directory', $options)) {
            throw new \InvalidArgumentException(
            	'File Cache require cache directory path in options. '.
                'Example: new FileCache(array(\'directory\' => \'/tmp\'));'
            );
        }
        $directory = $options['directory'];
        
        if (!is_dir($directory) && ! @mkdir($directory, 0777, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Cache directory "%s" does not exist and could not be created.',
                $directory
            ));
        }

        if (!is_writable($directory)) {
            throw new \InvalidArgumentException(sprintf(
                'The directory "%s" is not writable.',
                $directory
            ));
        }

        if (array_key_exists('dirlvl', $options)) {
            $dirlvl = (int)$options['dirlvl'];
            if ((int)$dirlvl > 32 ) {
                throw new \InvalidArgumentException('Dir Level cant be greater than 32.'); 
            }    
        }
        parent::setOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($key)
    {
        $item     = '';
        $lifetime = -1;
        $filename = $this->getFilename($key);

        if (!file_exists($filename)) {
            return false;
        }

        $resource = fopen($filename, "r");

        if (false !== ($line = fgets($resource))) {
            $lifetime = (integer) $line;
        }

        if ($lifetime !== 0 && $lifetime < time()) {
            fclose($resource);
            unlink($filename);
            return false;
        }

        while (false !== ($line = fgets($resource))) {
            $item .= $line;
        }

        fclose($resource);

        return unserialize($item);
    }

    /**
     * {@inheritdoc}
     */
    public function addItem($key, $item, $lifetime = 0)
    {
        $filename     = $this->getFileName($key);
        
        if ($this->hasItem($key)) {
            return false;
        }

        if ($lifetime > 0) {
            $lifetime = time() + $lifetime;
        }

        $item         = serialize($item);
        $filepath     = pathinfo($filename, PATHINFO_DIRNAME);

        if (!is_dir($filepath)) {
            mkdir($filepath, 0777, true);
        }

        return file_put_contents($filename, $lifetime . PHP_EOL . $item);
    }

    /**
     * {@inheritdoc}
     */
    public function setItem($key, $item, $lifetime = 0)
    {
        $item         = serialize($item);
        $filename     = $this->getFileName($key);
        $filepath     = pathinfo($filename, PATHINFO_DIRNAME);

        if ($lifetime > 0) {
            $lifetime = time() + $lifetime;
        }

        if (!is_dir($filepath)) {
            mkdir($filepath, 0777, true);
        }

        return file_put_contents($filename, $lifetime . PHP_EOL . $item);
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem($key)
    {
        $lifetime = -1;
        $filename = $this->getFilename($key);

        if (!file_exists($filename)) {
            return false;
        }

        $resource = fopen($filename, "r");

        if (false !== ($line = fgets($resource))) {
            $lifetime = (integer) $line;
        }

        if ($lifetime !== 0 && $lifetime < time()) {
            fclose($resource);
            unlink($filename);
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem($key)
    {
        return unlink($this->getFilename($key));
    }

    /**
     * Clear only expired cache files. 
     * This method should be called from cron.
     * 
     * @return boolean true if cleaning was successfu.l
     */
    public function clearExpired()
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->directory), 
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $path) {
            if (!$path->isDir()) {
                $lifetime = -1;

                $resource = fopen($path->__toString(), "r");

                if (false !== ($line = fgets($resource))) {
                    $lifetime = (integer) $line;
                }

                if ($lifetime !== 0 && $lifetime < time()) {
                    fclose($resource);
                    unlink($path->__toString());
                    continue;
                }
                fclose($resource);
            }
        }
 
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->directory), 
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($iterator as $path) {
            if ($path->isDir()) {
                rmdir($path->__toString());
            } else {
                unlink($path->__toString());
            }
        }
 
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clearNamespace($namespace)
    {
        $pattern  = '/' . $namespace . $this->namespaceSeparator . '(.+)$/i';
        $iterator = new \RecursiveDirectoryIterator($this->directory);
        $iterator = new \RecursiveIteratorIterator($iterator);
        $iterator = new \RegexIterator($iterator, $pattern);

        foreach ($iterator as $name => $file) {
            unlink($name);
        }
        return true;
    }

    /**
     * Returns full path to cache file for selected key. 
     * 
     * @return string
     */
    protected function getFileName($key)
    {
        $key = md5($key);
        $filePath = array();
        
        if ($this->dirlvl) {
            $filePath = array_slice(str_split($key, (floor(strlen($key) / $this->dirlvl))), 0, $this->dirlvl);
        }
        
        $path = $this->namespace . $this->namespaceSeparator . $key;
        $path = implode(DIRECTORY_SEPARATOR, $filePath) . DIRECTORY_SEPARATOR . $path;
        $path = $this->directory . DIRECTORY_SEPARATOR . $path;

        return $path;
    }
}