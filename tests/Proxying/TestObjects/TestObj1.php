<?php
namespace View\Proxying\TestObjects;

class TestObj1 {
	/**
	 * @return string
	 */
	public function getName() {
		return 'Jane "Doe"';
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return 'j.doe@example.org';
	}

	/**
	 * @return string
	 */
	public function getEmailWithName() {
		return 'Jane Doe <j.doe@example.org>';
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return sprintf('%s <%s>', $this->getName(), $this->getEmail());
	}

	/**
	 * @return string
	 */
	public function __invoke() {
		return sprintf('%s <%s>', $this->getName(), $this->getEmail());
	}
}
