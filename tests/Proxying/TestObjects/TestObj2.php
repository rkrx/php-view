<?php
namespace View\Proxying\TestObjects;

class TestObj2 {
	/**
	 * @return TestObj1[]
	 */
	public function getObjectsWithoutKeys() {
		return [
			new TestObj1(),
			new TestObj1(),
			new TestObj1(),
			new TestObj1(),
		];
	}
}