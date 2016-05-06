<?php
namespace View;

interface ViewFactory {
	/**
	 * @param string $baseDir
	 * @param array $vars
	 * @return mixed
	 */
	public function create($baseDir = null, array $vars = []);

	/**
	 * @param string $baseDir
	 * @param array $vars
	 * @return mixed
	 */
	public function deriveFactory($baseDir = null, array $vars = []);
}
