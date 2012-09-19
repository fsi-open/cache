## FSi Cache Component Documentation ##

Features:

- supports array, apc and file cache storages. New storages will be added in the future.
- supports namespaces inside of cache

By default all cache types use ``fsicache`` namespace by default, you can change it using setNamespace function:

    $cache->setNamespace('namespace-name');

or when cache instance is created

    $cache = new ArrayCache(array('namespace' => 'namespace-name'));

Opiton ``'namespace'`` can be used in all types of cache. 

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

## Samples ##

    <?php 
    
    use FSi\Component\Cache\ApcCache;
    
    // create apc cache instance 
    $cache = new ApcCache(); 
    
    // check if there is a foo
    if ($cache->hasItem('foo')) {
        echo 'foo exists in cache!';
    } else {
        // store foo-value in cache under key foo for 3600 seconds
        $cache->setItem('foo', 'foo-value', 3600);
    }
    ?>