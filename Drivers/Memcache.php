<?php

namespace Cache\Backends;

use Cache\CacheInterface,
    Cache\Exception;

class Memcache implements CacheInterface
{

    private $client;

    public function __construct()
    {
        if (!class_exists('\\Memcache'))
        {
            throw new Exception('Memcache is not available');
        }
        $this->client = new \Memcache();
    }

    public function addServer($host = 'localhost', $port = 11211)
    {
        if (!$this->client)
        {
            return;
        }
        $this->client->addServer($host, $port);
    }

    public function get($key)
    {
        if (!$this->client || ($value = $this->client->get($key)) === false)
        {
            return null;
        }
        return $value;
    }

    public function set($key, $value, $ttl)
    {
        if (!$this->client)
        {
            return;
        }
        $this->client->set($key, $value, 0, time() + $ttl);
    }

}
