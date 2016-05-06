<?php
namespace View;

interface ViewFactory {
	/**
	 * @param string $baseDir
	 * @param array $vars
	 * @return Renderer
	 */
	public function create($baseDir = null, array $vars = []);

	/**
	 * @param string $baseDir
	 * @param array $vars
	 * @return $this
	 */
	public function deriveFactory($baseDir = null, array $vars = []);
}
