<?php

namespace Cache;

class Cache implements Cache\CacheInterface
{

    protected static $Cache;
    
    protected $type;
    protected $class;

    const FILE = 'file';
	const MEMCACHE = 'memcache';
	const APC = 'apc';

	public static $Drivers = array(self::FILE, self::MEMCACHE, self::APC);


	public static function Instance() {
		if(is_null(self::$Cache)) {
			self::$Cache = new self();
		}
		return self::$Cache;
	}


    /**
     * Set value Of cache
     * @param String $key Cache ID
     * @param mixed $value this is actually what is returned
     * @param optional $expire
     * @return mixed
     */
	public function set($key, $value, $expire = NULL) {
		if($this->class) {
			return $this->class->set($key, $value, $expire);
		}
		return $value;
	}

    /**
     * Get value Of cache
     * @param String $key Cache ID
     * @return mixed
     */
	public function get($key) {
		if($this->class) {
			return $this->class->get($key);
		}
		return NULL;
	}

    /**
     * Seting a cache type
     * @param String $type 
     */
	public function setType($type) {
		if($type && !in_array($type, self::$Drivers)) {
			throw new \InvalidArgumentException('Unknown type');
		}
		$this->type = $type;
		switch($type) {
			case self::MEMCACHE:
				$this->class = new \Cache\Drivers\CacheMemcache();
			case self::APC:
				$this->class = new \Cache\Drivers\CacheApc();
		}
		$this->class = new \Cache\Drivers\CacheFile();
	}

    /**
     * Get cache type
     * @return String
     */
	public function getType() {
		return $this->type;
	}

    /**
     * Get cache driver
     * @return String
     */
	public function getClass() {
		return $this->class;
	}
}
