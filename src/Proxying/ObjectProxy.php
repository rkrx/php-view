<?php
namespace View\Proxying;

class ObjectProxy {
	/** @var object */
	private $object;
	/** @var ObjectProxyFactory */
	private $objectProxyFactory;

	/**
	 * @param object $object
	 * @param ObjectProxyFactory $objectProxyFactory
	 */
	public function __construct($object, ObjectProxyFactory $objectProxyFactory) {
		$this->object = $object;
		$this->objectProxyFactory = $objectProxyFactory;
	}

	/**
	 * @param string $name
	 * @return mixed|ObjectProxy
	 */
	public function __get($name) {
		$value = $this->object->{$name};
		$value = $this->objectProxyFactory->create($value);
		return $value;
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
	 * @param array $params
	 * @return mixed|ObjectProxy
	 */
	public function __call($name, array $params) {
		$value = call_user_func_array([$this->object, $name], $params);
		$value = $this->objectProxyFactory->create($value);
		return $value;
	}

	/**
	 * @return mixed|ObjectProxy
	 */
	public function __invoke() {
		$value = $this->object->__invoke();
		$value = $this->objectProxyFactory->create($value);
		return $value;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$value = $this->object->__toString();
		$value = $this->objectProxyFactory->getContext()->escape($value);
		return $value;
	}
}