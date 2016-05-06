<?php
namespace View\Helpers;

abstract class ViewTryFinallySimulator {
	/**
	 * @param callable $fn
	 * @param callable $finally
	 * @return mixed
	 * @throws \Exception
	 */
	public static function tryThis($fn, $finally) {
		$result = null;
		try {
			$result = call_user_func($fn);
		} catch (\Exception $e) {
			call_user_func($finally);
			throw $e;
		}
		call_user_func($finally);
		return $result;
	}
}
