<?php
namespace View;

use PHPUnit\Framework\TestCase;
use View\Proxying\TestObjects\TestObj1;
use View\Workers\FileWorker;
use View\Workers\FileWorker\FileWorkerConfiguration;

class ViewTest extends TestCase {
	protected function setUp(): void {
		parent::setUp();
		set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__);
	}

	public function testIncludeAndLayout(): void {
		$view = new Renderer(new FileWorker('templates/caseA'));
		$content = $view->render('index');

		$this->assertSame('![index include]', $content);
	}

	public function testEscaping(): void {
		$view = new Renderer(new FileWorker('templates/caseB'));
		$view->add('mode', 'esc');
		$view->add('obj', new TestObj1());
		$content = $view->render('index');
		$this->assertSame('Jane &quot;Doe&quot; &lt;j.doe@example.org&gt;', trim($content));
	}

	public function testUnescaping(): void {
		$view = new Renderer(new FileWorker('templates/caseB'));
		$view->add('mode', 'unesc');
		$view->add('obj', new TestObj1());
		$content = $view->render('index');
		$this->assertSame('Jane "Doe" <j.doe@example.org>', trim($content));
	}

	public function testRelativeDerectories(): void {
		$view = new Renderer(new FileWorker('templates/caseC'));
		$content = $view->render('subdir/index');
		$this->assertSame('[Hello World](Hello World)', trim($content));
	}

	public function testDelegates(): void {
		$view = new Renderer(new FileWorker('templates/caseC/subdir', null, [], null, new FileWorker('templates/caseC')));
		$content = $view->render('index');
		$this->assertSame('[Hello World](Hello World)', trim($content));
	}

	public function testVirtualPaths(): void {
		$paths = [
			'case-c' => __DIR__.'/templates/caseC',
			'case-d' => __DIR__.'/templates/caseD',
			'test' => __DIR__.'/templates/caseD',
		];
		$config = new FileWorkerConfiguration(null, null, null, ['paths' => $paths]);
		$view = new Renderer(new FileWorker('', null, [], $config, new FileWorker('templates')));
		$content = $view->render('@test/index');
		$this->assertSame('[Hello World](Hello World)[---]', trim($content));
	}
}
