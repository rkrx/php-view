<?php

namespace View\Proxying;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Generator;
use IteratorAggregate;
use RuntimeException;
use Stringable;
use Traversable;

class ArrayProxy implements ArrayAccess, Countable, IteratorAggregate, Stringable {
	/** @var array|Traversable */
	private $array;
	/** @var ObjectProxyFactory */
	private $objectProxyFactory;

	/**
	 * @param array|Traversable $array
	 * @param ObjectProxyFactory $objectProxyFactory
	 */
	public function __construct($array = [], ?ObjectProxyFactory $objectProxyFactory = null) {
		if(!$objectProxyFactory instanceof ObjectProxyFactory) {
			throw new RuntimeException('No object proxy factory given');
		}
		if($array instanceof Generator) {
			$array = iterator_to_array($array);
		}
		$this->array = $array;
		$this->objectProxyFactory = $objectProxyFactory;
	}

	public function getIterator(): Traversable {
		$result = [];
		foreach($this->getArray() as $key => $value) {
			$result[$key] = $this->objectProxyFactory->create($value);
		}

		return new ArrayIterator($result);
	}

	public function offsetExists(mixed $offset): bool {
		$array = $this->getArray();

		return array_key_exists($offset, $array);
	}

	public function offsetGet(mixed $offset): mixed {
		$array = $this->getArray();
		if(array_key_exists($offset, $array)) {
			return $this->objectProxyFactory->create($this->array[$offset]);
		}

		return null;
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		$array = $this->getArray();
		$array[$offset] = $value;
		$this->array = $array;
	}

	public function offsetUnset(mixed $offset): void {
		$array = $this->getArray();
		unset($array[$offset]);
		$this->array = $array;
	}

	public function count(): int {
		$array = $this->getArray();

		return count($array);
	}

	public function __toString(): string {
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
