<?php

namespace Cache\Drivers;

use Cache\CacheInterface;

class File implements CacheInterface {

	protected $Dir;

	public function __construct() {
		$this->Dir = (isset($_SERVER['DOCUMENT_ROOT']) ? dirname($_SERVER['DOCUMENT_ROOT']) 
		: dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'cache';
	}

	protected function getFile($key) {
		return $this->Dir . DIRECTORY_SEPARATOR . md5($key). '.cache';
	}

	public function set($key, $value, $expire = NULL) {
		if($value) {
			$handle = fopen($this->getFile($key), 'w+');
			$obj = array('Expires' => time() + $expire, 'Value' => $value);

			fwrite($handle, serialize($obj));
			fclose($handle);
		}
	}

	public function get($key) {
		if(file_exists($this->getFile($key))) {
			$obj = unserialize(file_get_contents($this->getFile($key)));
			if(is_array($obj) && isset($obj['Expires']) && $obj['Expires'] > time() && isset($obj['Value']) ) {
				return $obj['Value'];
			}
		}
		return NULL;
	}
}