<?php
namespace Kir\View;

use Kir\View\Workers\FileWorker;

class ViewTest extends \PHPUnit_Framework_TestCase {
	public function testAll() {
		$view = new View(new FileWorker(__DIR__.'/templates'));
		$content = $view->render('index');

		$this->assertEquals('![index include]', $content);
	}
}
