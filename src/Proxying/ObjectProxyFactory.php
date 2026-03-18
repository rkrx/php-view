<?php

namespace View\Proxying;

use Traversable;
use View\Contexts\Context;

class ObjectProxyFactory {
	public function __construct(private readonly Context $context) {}

	/**
	 * @return Context
	 */
	public function getContext() {
		return $this->context;
	}

	/**
	 * @param object|array|bool|int|float|string|resource|null $object
	 * @return ObjectProxy|mixed
	 */
	public function create($object) {
		if($object === null) {
			$proxy = '';
		} elseif(is_resource($object)) {
			$proxy = $object;
		} elseif(is_iterable($object)) {
			$proxy = new ArrayProxy($object, $this);
		} elseif(is_object($object)) {
			$proxy = new ObjectProxy($object, $this);
		} else {
			$proxy = $this->context->escape($object);
		}

		return $proxy;
	}
}
