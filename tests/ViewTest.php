<?php
namespace View;

use View\Proxying\TestObjects\TestObj1;
use View\Workers\FileWorker;

class ViewTest extends \PHPUnit_Framework_TestCase {
	protected function setUp() {
		parent::setUp();
		set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__);
	}

	/**
	 */
	public function testIncludeAndLayout() {
		$view = new Renderer(new FileWorker('templates/caseA'));
		$content = $view->render('index');

		$this->assertEquals('![index include]', $content);
	}

	/**
	 */
	public function testEscaping() {
		$view = new Renderer(new FileWorker('templates/caseB'));
		$view->add('mode', 'esc');
		$view->add('obj', new TestObj1());
		$content = $view->render('index');
		$this->assertEquals('Jane &quot;Doe&quot; &lt;j.doe@example.org&gt;', trim($content));
	}

	/**
	 */
	public function testUnescaping() {
		$view = new Renderer(new FileWorker('templates/caseB'));
		$view->add('mode', 'unesc');
		$view->add('obj', new TestObj1());
		$content = $view->render('index');
		$this->assertEquals('Jane "Doe" <j.doe@example.org>', trim($content));
	}

	/**
	 */
	public function testRelativeDerectories() {
		$view = new Renderer(new FileWorker('templates/caseC'));
		$content = $view->render('subdir/index');
		$this->assertEquals('[Hello World](Hello World)', trim($content));
	}

	/**
	 */
	public function testDelegates() {
		$view = new Renderer(new FileWorker('templates/caseC/subdir', null, [], null, new FileWorker('templates/caseC')));
		$content = $view->render('index');
		$this->assertEquals('[Hello World](Hello World)', trim($content));
	}
}
