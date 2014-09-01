<?php
namespace Kir\Templating\View\Helpers\Escaping;

class HtmlEscapingTest extends \PHPUnit_Framework_TestCase {
	public function testEscape() {
		$escaping = new HtmlEscaping();
		$this->assertEquals('&lt;script /&gt;', $escaping->escape('<script />'));
	}

	public function testUnescape() {
		$escaping = new HtmlEscaping();
		$this->assertEquals('<script />', $escaping->unescape('&lt;script /&gt;'));
	}
}
 