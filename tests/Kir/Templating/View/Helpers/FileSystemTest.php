<?php
namespace Kir\Templating\View\Helpers;

class FileSystemTest extends \PHPUnit_Framework_TestCase {
	public function testGetFileExt() {
		$this->assertEquals('phtml', FileSystem::getFileExt('test.phtml'));
	}
}
 