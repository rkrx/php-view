<?php
namespace View\Proxying;

use View\Contexts\HtmlContext;
use View\Proxying\TestObjects\TestObj1;
use View\Proxying\TestObjects\TestObj2;

class ObjectProxyTest extends \PHPUnit_Framework_TestCase {
	public function testMethodCalls() {
		$testobj = new TestObj1();
		$context = new HtmlContext();
		$factory = new ObjectProxyFactory($context);

		/** @var TestObj1 $proxy */
		$proxy = new ObjectProxy($testobj, $factory);

		$this->assertEquals('Jane &quot;Doe&quot;', $proxy->getName());
		$this->assertEquals('Jane Doe &lt;j.doe@example.org&gt;', $proxy->getEmailWithName());
		$this->assertEquals('Jane &quot;Doe&quot; &lt;j.doe@example.org&gt;', (string) $proxy);
		$this->assertEquals('Jane &quot;Doe&quot; &lt;j.doe@example.org&gt;', $proxy());
	}

	public function testWalkThroughStructures() {
		$testobj = new TestObj2();
		$context = new HtmlContext();
		$factory = new ObjectProxyFactory($context);

		/** @var TestObj2 $proxy */
		$proxy = new ObjectProxy($testobj, $factory);

		$array = $proxy->getObjectsWithoutKeys();
		foreach(range(0, 3) as $key) {
			$testObj = $array[$key];
			$this->assertEquals('Jane &quot;Doe&quot;', $testObj->getName());
			$this->assertEquals('Jane Doe &lt;j.doe@example.org&gt;', $testObj->getEmailWithName());
			$this->assertEquals('Jane &quot;Doe&quot; &lt;j.doe@example.org&gt;', (string) $testObj);
			$this->assertEquals('Jane &quot;Doe&quot; &lt;j.doe@example.org&gt;', $testObj());
		}
	}
}
