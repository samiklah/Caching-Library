<?php

namespace Cache\Drivers;

use Cache\CacheInterface;

class APC implements CacheInterface
{
    /**
     * Get value from cache
     * @param String $key Cache ID
     * @return mixed
     */
    public function get($key)
    {
        if (($value = apc_fetch($key)) === false)
        {
            return null;
        }
        return unserialize($value);
    }

    /**
     * Set value of cache entry
     * @param String $key Cache ID
     * @param mixed $value Value
     * @param optional Integer $ttl Time-to-live of cache entry
     */
    public function set($key, $value, $ttl)
    {
        apc_store($key, serialize($value), $ttl);
    }

}
