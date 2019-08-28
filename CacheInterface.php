<?php

namespace Cache;

interface CacheInterface
{

    public function get($key);
    public function set($key, $value, $ttl);

}
