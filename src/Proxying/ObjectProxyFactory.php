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
	 * @return ObjectProxy
	 */
	public function create($object) {
		$proxy = null;
		if(is_scalar($object)) {
			$proxy = $this->context->escape($object);
		} elseif(is_array($object) || $object instanceof Traversable) {
			$proxy = array();
			foreach($object as $key => $value) {
				$proxy[$key] = $this->create($value);
			}
		} else {
			$proxy = new ObjectProxy($object, $this);
		}
		return $proxy;

	}
}