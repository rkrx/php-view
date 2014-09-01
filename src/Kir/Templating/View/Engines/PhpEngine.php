<?php
namespace Kir\Templating\View\Engines;

use Kir\Data\Arrays\RecursiveAccessor\StringPath;
use Kir\Templating\View\Cache;
use Kir\Templating\View\Engine;
use Kir\Templating\View\Caches\ArrayCache;
use Kir\Templating\View\Helpers\Escaping;

class PhpEngine implements Engine {
	/**
	 * @var Cache
	 */
	private $cache = null;

	/**
	 * @var StringPath\Map
	 */
	private $map = null;

	/**
	 * @param string $templateDir
	 * @param Cache $cache
	 * @param Escaping $escaping
	 */
	public function __construct($templateDir, Cache $cache = null, Escaping $escaping) {
		if($cache === null) {
			$cache = new ArrayCache();
		}
		$this->cache = $cache;
		$this->map = new StringPath\Map();
	}

	/**
	 * @param string $baseDir
	 * @param string $filename
	 * @param array $arguments
	 * @return string
	 */
	public function render($baseDir, $filename, array $arguments) {
		// TODO: Implement render() method.
	}
}