<?php
namespace View\Proxying;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class ArrayProxy implements ArrayAccess, Countable, IteratorAggregate {
	/** @var array|Traversable */
	private $array;
	/** @var ObjectProxyFactory */
	private $objectProxyFactory;

	/**
	 * @param array|Traversable $array
	 * @param ObjectProxyFactory $objectProxyFactory
	 */
	public function __construct($array = [], ObjectProxyFactory $objectProxyFactory) {
		$this->array = $array;
		$this->objectProxyFactory = $objectProxyFactory;
	}

	/**
	 * @return Traversable
	 */
	public function getIterator() {
		$result = [];
		foreach($this->getArray() as $key => $value) {
			$result[$key] = $this->objectProxyFactory->create($value);
		}
		return new ArrayIterator($result);
	}

	/**
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset) {
		return array_key_exists($this->array, $offset);
	}

	/**
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset) {
		$array = $this->getArray();
		if(array_key_exists($offset, $array)) {
			$value = $this->objectProxyFactory->create($this->array[$offset]);
			return $value;
		}
		return null;
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 * @return void
	 */
	public function offsetSet($offset, $value) {
		$array = $this->getArray();
		$array[$offset] = $value;
		$this->array = $array;
	}

	/**
	 * @param mixed $offset
	 * @return void
	 */
	public function offsetUnset($offset) {
		$array = $this->getArray();
		unset($array[$offset]);
		$this->array = $array;
	}

	/**
	 * @return int
	 */
	public function count() {
		$array = $this->getArray();
		return count($array);
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return '';
	}

	/**
	 * @return array
	 */
	private function getArray() {
		if($this->array instanceof Traversable) {
			$tmp = [];
			foreach($tmp as $key => $value) {
				$tmp[$key] = $value;
			}
			$this->array = $tmp;
		}
		return $this->array;
	}
}
