<?php

namespace View\Helpers;

use Stringable;

class StringBucket implements \Stringable {
	/**
	 * @param string|Stringable $data
	 * @param string $data
	 */
	public function __construct(private bool|int|float|string|Stringable $data) {}

	public function __toString(): string {
		return (string) $this->data;
	}
}
