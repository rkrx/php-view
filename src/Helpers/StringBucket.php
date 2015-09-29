<?php
namespace View\Helpers;

class StringBucket {
	/** @var string */
	private $data;

	/**
	 * @param $data
	 */
	public function __construct($data) {
		$this->data = $data;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->data;
	}
}
