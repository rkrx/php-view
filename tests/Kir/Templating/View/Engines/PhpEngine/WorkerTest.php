<?php
namespace Kir\Templating\View\Engines\PhpEngine;

use Kir\Templating\View\Helpers\Map;

class WorkerTest extends \PHPUnit_Framework_TestCase {
	public function test1() {
		$worker = new Worker(__DIR__.'/WorkerTest', '.phtml', new Map());
		$content = $worker->render('test1');
		$this->assertEquals('Hello world', trim($content));
	}

	public function test2_1() {
		$worker = new Worker(__DIR__.'/WorkerTest', '.phtml', new Map());
		$worker->set('a.b.c', '<world>');
		$content = $worker->render('test2');
		$this->assertEquals('Hello &lt;world&gt;', trim($content));
	}
}
 