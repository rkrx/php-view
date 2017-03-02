<?php
namespace View;

interface ViewFactory {
	/**
	 * @param string $name
	 * @param string $path
	 * @return $this
	 */
	public function addPath($name, $path);
	
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
