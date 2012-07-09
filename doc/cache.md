# FSi Cache Component Documentation

Features:

- supports many cache storages
- supports namespaces inside cache

Content:

- [Usage](#usage)
- [Array Cache](#array-cache)
- [Apc Cache](#apc-cache)
- [Samples](#samples)

## Usage {#usage}

FSi Cache component usage is extremely easy. All you need to do is add use FSi\Component\Cache namespace into code and create instance of cache. FSi Cache is standaolne component so you dont need do download any other components to use it. 
Its highly recommended to use Symfony Autoloader for autoloading required files. 

## Array Cache {#array-cache} 

Array cache should be used only in development environmen to simulate normall cache behavior.

## APC Cache {#array-cache} 

Apc cache require apc extension eneabled in webserv. 
Informations about APC can be found at php.net (http://php.net/manual/en/book.apc.php)


## Samples 

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
        
        /* 
        * By default all cache instances use fsicache namespace
        * you can change namespace by:
        * $cache->setNamespace('namespace-name');
        * 
        * or when cache is created
        * $cache = new ArrayCacye(array('namespace' => 'namespace-name'));
        */
        ?>
