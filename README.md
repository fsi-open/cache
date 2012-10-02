## FSi Cache Component Documentation ##

Features:

- supports array, apc and file cache storages. New storages will be added in the future.
- supports namespaces inside of cache

By default all cache types use ``fsicache`` namespace by default, you can change it using setNamespace function:

    $cache->setNamespace('namespace-name');

or when cache instance is created. Opiton ``'namespace'`` can be used in all types of cache. 

    $cache = new ArrayCache(array('namespace' => 'namespace-name'));

there is also a possibility to pass namespace as optional parameter into methods:

- ``getItem($key, $namespace = null)``
- ``hasItem($key, $namespace = null)``
- ``addItem($key, $item, $lifetime = 0, $namespace = null)``
- ``setItem($key, $item, $lifetime = 0, $namespace = null)``
- ``removeItem($key, $namespace = null)``

If ``$namespace`` is null the current namespace is taken from method ``getNamespace()``. 

## Array Cache ##

Array cache should be used only in development environmen to simulate normall cache behavior.

**Example:**

    $cache = new ArrayCache();

## APC Cache ##

Apc cache require apc extension eneabled in webserv. 
Informations about APC can be found at [php.net](http://php.net/manual/en/book.apc.php)

**Example:**

    $cache = new ApcCache();

## File Cache ##

File cache require cache directory path inside of variable ``$options['directory']`` that is passed in constructor
There is also an additional parameter ``$options['dirlvl']`` that describe how deep cache should be nested. 
``dirlvl`` parameter might be useful when you know that cache will hold a big amount of files. Higher ``dirlvl`` means less files in signle cache directory. 

**Example:**

    $cache = new FileCache(array('directory' => '/tmp', 'dirlvl' => 3));

## Examples ##

**Basic Usage**

    <?php 
    
    use FSi\Component\Cache\ApcCache;
    
    // create apc cache instance with default namespace 
    $cache = new ApcCache(); 
    
    // check if there is a foo
    if ($cache->hasItem('foo')) {
        echo 'foo exists in cache!';
    } else {
        // store foo-value in cache under key foo for 3600 seconds
        $cache->setItem('foo', 'foo-value', 3600);
    }
    ?>

**Namespace Usage**

    <?php 
    
    use FSi\Component\Cache\ApcCache;
    
    // create apc cache instance with default namespace 
    $cache = new ApcCache(); 
    
    $cache->setItem('key1', 'test', 0, 'testnamespace1');
    $cache->setItem('key2', 'test', 0, 'testnamespace2');
         
    $cache->hasItem('key1', 'testnamespace1'); // will return true
    $cache->hasItem('key2', 'testnamespace2'); // will return true
    
    $cache->hasItem('key2', 'testnamespace1'); //will return false
    $cache->hasItem('key1', 'testnamespace2'); //will return false
    ?>