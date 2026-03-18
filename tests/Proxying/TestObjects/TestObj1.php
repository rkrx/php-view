<?php
namespace View\Proxying\TestObjects;

class TestObj1 implements \Stringable {
	public function getName(): string {
		return 'Jane "Doe"';
	}

	public function getEmail(): string {
		return 'j.doe@example.org';
	}

	public function getEmailWithName(): string {
		return 'Jane Doe <j.doe@example.org>';
	}

	public function __toString(): string {
		return sprintf('%s <%s>', $this->getName(), $this->getEmail());
	}

	public function __invoke(): string {
		return sprintf('%s <%s>', $this->getName(), $this->getEmail());
	}
}
