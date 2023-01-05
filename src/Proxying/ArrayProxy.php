<?php
namespace View\Proxying;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Generator;
use IteratorAggregate;
use RuntimeException;
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
	public function __construct($array = [], ?ObjectProxyFactory $objectProxyFactory = null) {
		if($objectProxyFactory === null) {
			throw new RuntimeException('No object proxy factory given');
		}
		if($array instanceof Generator) {
			$array = iterator_to_array($array);
		}
		$this->array = $array;
		$this->objectProxyFactory = $objectProxyFactory;
	}

	/**
	 * @return Traversable
	 */
	#[\ReturnTypeWillChange]
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
	#[\ReturnTypeWillChange]
	public function offsetExists($offset) {
		$array = $this->getArray();
		return array_key_exists($offset, $array);
	}

	/**
	 * @param mixed $offset
	 * @return mixed
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet($offset) {
		$array = $this->getArray();
		if(array_key_exists($offset, $array)) {
			return $this->objectProxyFactory->create($this->array[$offset]);
		}
		return null;
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 * @return void
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet($offset, $value) {
		$array = $this->getArray();
		$array[$offset] = $value;
		$this->array = $array;
	}

	/**
	 * @param mixed $offset
	 * @return void
	 */
	public function offsetUnset($offset): void {
		$array = $this->getArray();
		unset($array[$offset]);
		$this->array = $array;
	}

	/**
	 * @return int
	 */
	public function count(): int {
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
			$this->array = iterator_to_array($this->array);
		}
		return $this->array;
	}
}
