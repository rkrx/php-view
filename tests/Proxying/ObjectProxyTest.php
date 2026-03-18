<?php
namespace View\Proxying;

use PHPUnit\Framework\TestCase;
use View\Contexts\HtmlContext;
use View\Proxying\TestObjects\TestObj1;
use View\Proxying\TestObjects\TestObj2;

class ObjectProxyTest extends TestCase {
	public function testMethodCalls(): void {
		$testobj = new TestObj1();
		$context = new HtmlContext();
		$factory = new ObjectProxyFactory($context);

		/** @var TestObj1 $proxy */
		$proxy = new ObjectProxy($testobj, $factory);

		$this->assertSame('Jane &quot;Doe&quot;', $proxy->getName());
		$this->assertSame('Jane Doe &lt;j.doe@example.org&gt;', $proxy->getEmailWithName());
		$this->assertSame('Jane &quot;Doe&quot; &lt;j.doe@example.org&gt;', (string) $proxy);
		$this->assertSame('Jane &quot;Doe&quot; &lt;j.doe@example.org&gt;', $proxy());
	}

	public function testWalkThroughStructures(): void {
		$testobj = new TestObj2();
		$context = new HtmlContext();
		$factory = new ObjectProxyFactory($context);

		/** @var TestObj2 $proxy */
		$proxy = new ObjectProxy($testobj, $factory);

		$array = $proxy->getObjectsWithoutKeys();
		foreach(range(0, 3) as $key) {
			$testObj = $array[$key];
			$this->assertSame('Jane &quot;Doe&quot;', $testObj->getName());
			$this->assertSame('Jane Doe &lt;j.doe@example.org&gt;', $testObj->getEmailWithName());
			$this->assertSame('Jane &quot;Doe&quot; &lt;j.doe@example.org&gt;', (string) $testObj);
			$this->assertSame('Jane &quot;Doe&quot; &lt;j.doe@example.org&gt;', $testObj());
		}
	}
}
