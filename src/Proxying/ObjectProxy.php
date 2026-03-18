<?php

namespace View\Proxying;

class ObjectProxy implements \Stringable {
	/**
	 * @param object $object
	 */
	public function __construct(
		private object $object,
		private readonly ObjectProxyFactory $objectProxyFactory
	) {}

	/**
	 * @param string $name
	 * @return mixed|ObjectProxy
	 */
	public function __get($name) {
		$value = $this->object->{$name};

		return $this->objectProxyFactory->create($value);
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value) {
		$this->object->{$name} = $value;
	}

	/**
	 * @param string $name
	 * @return mixed|ObjectProxy
	 */
	public function __call($name, array $params) {
		$value = call_user_func_array([$this->object, $name], $params);

		return $this->objectProxyFactory->create($value);
	}

	/**
	 * @return mixed|ObjectProxy
	 */
	public function __invoke() {
		$value = $this->object->__invoke();

		return $this->objectProxyFactory->create($value);
	}

	public function __toString(): string {
		$value = $this->object->__toString();

		return $this->objectProxyFactory->getContext()->escape($value);
	}
}
