<?php

namespace Cache;

use Cache\CacheInterface;

class Cache
{

    private $backend; // Current Cache backend
    private $namespace = ''; // Current namespace
    private $dTTL = self::TTL; // Default TTL of Cache instance
    private $separator = ':'; // Separator between parts of namespace and keys

    private static $instances = array();

    const INSTANCE = 'default'; // Default instance name
    const TTL = 0; // Infinite time-to-live

    /**
     * @param optional CacheInterface $backend Instance of cache backend
     */
    public function __construct(CacheInterface $backend = null)
    {
        if ($backend)
        {
            $this->setBackend($backend);
        }
    }

    /**
     * Returns a Cache instance
     * @param String String $name Name of instance, default INSTANCE
     * @param optional CacheInterface $backend Instance of cache backend
     * @return CacheInterface
     */
    public static function instance(string $name = null, CacheInterface $backend = null)
    {
        if ($name === null)
        {
            $name = self::INSTANCE;
        }
        if (isset(self::$instances[$name]) === false)
        {
            self::$instances[$name] = new Cache($backend);
        }
        return self::$instances[$name];
    }


    /**
     * Set cache backend
     * @param CacheInterface $backend
     * @return self
     */
    public function setBackend(CacheInterface $backend)
    {
        $this->backend = $backend;
        return $this;
    }

    public function setNamespace($ns)
    {
        $this->backend = $backend;
        return $this;
    }

    public function setKeySeparator($separator)
    {
        $this->separator = $separator;
        return $this;
    }

    private function setKey($key)
    {
        if ($this->namespace)
        {
            $key = $this->namespace . $this->separator . $key;
        }
        return $key;
    }


    public function get($key)
    {
        return $this->backend->get($this->setKey($key));
    }

    public function set($key, $value, $ttl = null)
    {
        $this->backend->set($this->setKey($key), $value, $ttl ?: $this->dTTL);
        return $this;
    }

    /**
     * Get value from $clo result if cache ID is not set, then returns value
     * of cache entry
     * @param String $key Cache ID
     * @param Closure $clo Closure executed if cache entry is not set
     * @param optional Integer $ttl Time-to-live of cache entry
     * @return mixed
     */
    public function getset($key, \Closure $clo, $ttl = null)
    {
        $key = $this->setKey($key);
        if (($value = $this->get($key)) === null)
        {
            $value = $clo();
            $this->set($key, $value, $ttl ?: $this->dTTL);
        }
        return $value;
    }

    /**
     * Get namespace from this cache instance
     * @param String $key Name of namespace (prefix of cache ID)
     * @return Cache
     */
    public function ns($key)
    {
        $cache = new Cache();
        return $cache
            ->setBackend($this->backend)
            ->setKeySeparator($this->separator)
            ->setNamespace($this->setKey($key));
    }

    

}
