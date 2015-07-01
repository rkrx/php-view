<?php
namespace View\Proxying;

use Traversable;
use View\Contexts\Context;

class ObjectProxyFactory {
	/** @var Context */
	private $context;

	/**
	 * @param Context $context
	 */
	public function __construct(Context $context) {
		$this->context = $context;
	}

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
		} elseif(is_object($object)) {
			if(!is_object($object)) {
				$object = (object) $object;
			}
			$proxy = new ObjectProxy($object, $this);
		} elseif(is_array($object) || $object instanceof Traversable) {
			$proxy = new ArrayProxy($object, $this);
		} else {
			$proxy = $this->context->escape($object);
		}
		return $proxy;
	}
}
